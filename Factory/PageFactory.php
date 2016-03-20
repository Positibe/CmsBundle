<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Factory;

use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;
use Positibe\Bundle\OrmContentBundle\Entity\MenuNode;
use Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractPage;
use Positibe\Bundle\OrmContentBundle\Entity\Page;
use Positibe\Bundle\OrmMediaBundle\Entity\Media;
use Positibe\Bundle\OrmMediaBundle\Provider\ImageProvider;
use Positibe\Bundle\OrmMenuBundle\Menu\Factory\ContentAwareFactory;
use Positibe\Bundle\OrmRoutingBundle\Entity\Route;
use Positibe\Bundle\OrmRoutingBundle\Factory\RouteFactory;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;


/**
 * Class PageFactory
 * @package Positibe\Bundle\OrmContentBundle\Factory
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageFactory
{
    const DEFAULT_CONTROLLER = 'PositibeOrmContentBundle:Default:index';
    private $menuFactory;
    private $routeFactory;
    private $em;
    private $locale;

    /**
     * @param MenuNodeFactory $menuFactory
     * @param RouteFactory $routeFactory
     * @param EntityManager $entityManager
     * @param $locale
     */
    public function __construct(
        MenuNodeFactory $menuFactory,
        RouteFactory $routeFactory,
        EntityManager $entityManager,
        $locale
    ) {
        $this->menuFactory = $menuFactory;
        $this->routeFactory = $routeFactory;
        $this->em = $entityManager;
        $this->locale = $locale;
    }

    /**
     * @param AbstractPage $page
     */
    public function updateNodeMenus(AbstractPage $page)
    {
        /** @var MenuNode $menu */
        foreach ($page->getMenuNodes()->toArray() as $menu) {
            if (!$menu->getId()) {
                $menu->setLinkType(ContentAwareFactory::LINK_TYPE_CONTENT);
                $menu->setContent($page);
            }
        }
    }

    /**
     * @param AbstractPage $page
     * @param bool $auto
     */
    public function updateRoutes(AbstractPage $page, $auto = true)
    {
        $currentLocale = $this->getLocale($page);
        $needRoute = true;
        /** @var Route $route */
        foreach ($page->getRoutes()->toArray() as $route) {
            $route->setStaticPrefix($this->fixStaticPrefix($route->getStaticPrefix(), $page));
            if (!$route->getId()) {
                $route->setContent($page);
//                $route->setDefault('_controller', self::DEFAULT_CONTROLLER);
            }

            if ($auto && $needRoute && $route->getLocale() === $currentLocale) {
                $needRoute = false;
            }
        }

        if ($auto && $needRoute) {
            $route = $this->routeFactory->createContentRoute(
                $this->fixStaticPrefix(null, $page),
                $page,
                null
            );
            $route->setLocale($currentLocale);
            $page->addRoute($route);
        }
    }

    public function getLocale(AbstractPage $page)
    {
        return $page->getLocale() ? $page->getLocale() : $this->locale;
    }

    public function fixStaticPrefix($staticPrefix, AbstractPage $page)
    {
        if (empty($staticPrefix)) {
            $staticPrefix = $this->insertPartBeforePath($page->getTitle());

            $locale = $page->getLocale();
            $pageWithParent = $page;
            while ($pageWithParent->getParent() !== null) {
                $parent = $pageWithParent->getParent();

                if (!empty($locale)) {
                    $parent->setLocale($locale);
                    $this->em->refresh($parent);
                }
                $staticPrefix = $this->insertPartBeforePath($parent->getTitle(), $staticPrefix);
                $pageWithParent = $parent;
            }
        } else {
            $staticPrefix = Urlizer::urlize($staticPrefix);
            if (!preg_match('/^\//', $staticPrefix)) {
                $staticPrefix = '/' . $staticPrefix;
            }
        }

        return $staticPrefix;
    }

    /**
     * @param $path
     * @param $part
     * @return string
     */
    private function insertPartBeforePath($part, $path = '')
    {
        return '/' . Urlizer::urlize($part) . $path;
    }

    public function createPageCategory($title, $body, $path, $parentMenu, $locale = null, $imagePath = null)
    {
        $page = $this->createPage(
            $title,
            $body,
            $path,
            $parentMenu,
            $locale,
            $imagePath,
            'Positibe\Bundle\OrmContentBundle\Entity\Category'
        );

        return $page;
    }

    public function createPage($title, $body, $path, $parentMenu, $locale = null, $imagePath = null, $class = null)
    {
        //Creating the Page
        $page = empty($class) ? new Page() : new $class();
        $page->setName(Urlizer::urlize($title));
        $page->setTitle($title);
        $page->setBody($body);

        $seoMetadata = $this->updateSeoMetadata($page);
        $route = $this->createRoute($path, $page);
        $menu = $this->createMenuNode($page, $parentMenu, $locale);

        if ($locale !== null) {
            $page->setLocale($locale);
            $seoMetadata->setLocale($locale);
        }

        $page->setSeoMetadata($seoMetadata);
        $page->addRoute($route);

        if ($menu) {
            $page->addMenuNode($menu);
        }

        if ($imagePath) {
            $media = new Media();
            $media->setBinaryContent($imagePath);
            $media->setName($page->getName()); // video related to the user
            $media->setProviderName(ImageProvider::getName());
            $page->setImage($media);
        }

        return $page;
    }

    /**
     * Create the seo metadata information for the content
     *
     * @param AbstractPage $page
     * @return AbstractPage
     */
    public function updateSeoMetadata(AbstractPage $page)
    {
        $seoMetadata = $page->getSeoMetadata();
        if ($seoMetadata === null) {
            $seoMetadata = new SeoMetadata();
            $page->setSeoMetadata($seoMetadata);
        }
        if (!($seoMetadata->getTitle())) {
            $seoMetadata->setTitle($page->getTitle());
        }
        if (!($seoMetadata->getMetaDescription())) {
            $description = substr(trim(strip_tags($page->getBody())), 0, 150);
            $seoMetadata->setMetaDescription($description);
        }

        return $seoMetadata;
    }

    /**
     * Get the route for the content
     *
     * @param $path
     * @param AbstractPage $page
     * @return Route
     */
    public function createRoute($path, AbstractPage $page)
    {
        return $this->routeFactory->createContentRoute($path, $page, null);
    }

    /**
     * Get the menu for the content if have a parent menu
     *
     * @param AbstractPage $page
     * @param MenuNode $parentMenu
     * @param null $locale
     * @return null|MenuNode
     */
    public function createMenuNode(AbstractPage $page, MenuNode $parentMenu = null, $locale = null)
    {
        if ($parentMenu !== null) {
            $menu = $this->menuFactory->createContentMenuNode($page, $page->getTitle(), $parentMenu);
            $menu->setPage($page);

            if ($locale !== null) {
                $menu->setLocale($locale);
            }

            return $menu;
        }

        return null;
    }
} 
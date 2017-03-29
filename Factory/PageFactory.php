<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Factory;

use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;
use Positibe\Bundle\CmfRoutingExtraBundle\Entity\AutoRoute;
use Positibe\Bundle\CmfRoutingExtraBundle\Factory\RouteFactory;
use Positibe\Bundle\CmsBundle\Entity\MenuNode;
use Positibe\Bundle\CmsBundle\Entity\Page;
use Positibe\Bundle\MediaBundle\Entity\Media;
use Positibe\Bundle\MediaBundle\Provider\ImageProvider;
use Positibe\Bundle\MenuBundle\Menu\Factory\ContentAwareFactory;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;


/**
 * Class PageFactory
 * @package Positibe\Bundle\CmsBundle\Factory
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageFactory implements FactoryInterface
{
    const DEFAULT_CONTROLLER = 'PositibeCmsBundle:Default:index';
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
     * Create a new resource.
     *
     * @return object
     */
    public function createNew()
    {
        return new Page();
    }


    public function createNewByParentName($name)
    {
        /** @var Page $category */
        $category = $this->em->createQueryBuilder()->select('c')->from('PositibeCmsBundle:Page', 'c')
            ->where('c.name = :name')
            ->setParameter('name', $name)->getQuery()->getOneOrNullResult();

        /** @var Page $staticContent */
        $staticContent = $this->createNew();
        $staticContent->setCategory($category);

        return $staticContent;
    }

    /**
     * @param Page $page
     */
    public function updateNodeMenus(Page $page)
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
     * @param Page $page
     * @param bool $auto
     */
    public function updateRoutes(Page $page, $auto = true)
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

            if ($auto && $needRoute && $route->getDefault('_locale') === $currentLocale) {
                $needRoute = false;
            }
        }

        if ($auto && $needRoute) {
            $route = $this->routeFactory->createContentAutoRoute(
                $this->fixStaticPrefix(null, $page),
                $page,
                null
            );
            $route->setDefault('_locale', $currentLocale);
            $route->setRequirement('_locale', $currentLocale);
            $page->addRoute($route);
        }
    }

    public function getLocale(Page $page)
    {
        return $page->getLocale() ? $page->getLocale() : $this->locale;
    }

    public function fixStaticPrefix($staticPrefix, Page $page)
    {
        if (empty($staticPrefix)) {
            $staticPrefix = $this->insertPartBeforePath($page->getTitle());

            $locale = $page->getLocale();
            $pageWithParent = $page;
            while ($pageWithParent instanceof Page && $pageWithParent->getCategory() !== null) {
                $category = $pageWithParent->getCategory();

                if (!empty($locale)) {
                    $category->setLocale($locale);
                    $this->em->refresh($category);
                }
                $staticPrefix = $this->insertPartBeforePath($category->getTitle(), $staticPrefix);
                $pageWithParent = $category;
            }
        } else {
            $staticPrefix = Urlizer::urlize($staticPrefix);
            if (!preg_match('/^\//', $staticPrefix)) {
                $staticPrefix = '/'.$staticPrefix;
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
        return '/'.Urlizer::urlize($part).$path;
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
            'Positibe\Bundle\CmsBundle\Entity\Category'
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
        $page->setPublishable(true);

        $seoMetadata = $this->updateSeoMetadata($page);
        $route = $this->createRoute($path, $page, null, $locale);
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
     * @param Page $page
     * @return Page
     */
    public function updateSeoMetadata(Page $page)
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
     * @param Page $page
     * @return Route
     */
    /**
     * @param $path
     * @param Page $page
     * @param null $controller
     * @param null $locale
     * @return AutoRoute
     */
    public function createRoute($path, Page $page, $controller = null, $locale = null)
    {
        return $this->routeFactory->createContentAutoRoute($path, $page, $controller, $locale);
    }

    /**
     * Get the menu for the content if have a parent menu
     *
     * @param Page $page
     * @param MenuNode $parentMenu
     * @param null $locale
     * @return null|MenuNode
     */
    public function createMenuNode(Page $page, MenuNode $parentMenu = null, $locale = null)
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
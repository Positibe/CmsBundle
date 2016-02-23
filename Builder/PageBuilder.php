<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Builder;

use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;
use Positibe\Bundle\OrmContentBundle\Entity\Page;
use Positibe\Bundle\OrmContentBundle\Entity\StaticContent;
use Positibe\Bundle\OrmContentBundle\Model\ContentType;
use Positibe\Bundle\OrmMenuBundle\Builder\MenuBuilder;
use Positibe\Bundle\OrmMenuBundle\Entity\MenuNode;
use Positibe\Bundle\OrmMenuBundle\Menu\Factory\ContentAwareFactory;
use Positibe\Bundle\OrmRoutingBundle\Builder\RouteBuilder;
use Positibe\Bundle\OrmRoutingBundle\Entity\Route;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;


/**
 * Class PageBuilder
 * @package Positibe\Bundle\OrmContentBundle\Content
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageBuilder
{
    const DEFAULT_CONTROLLER = 'PositibeOrmContentBundle:Default:index';
    private $menuBuilder;
    private $routeBuilder;
    private $em;
    private $locale;
    private $controllerConfiguration;

    /**
     * @param MenuBuilder $menuBuilder
     * @param RouteBuilder $routeBuilder
     * @param EntityManager $entityManager
     * @param $locale
     */
    public function __construct(
        MenuBuilder $menuBuilder,
        RouteBuilder $routeBuilder,
        EntityManager $entityManager,
        $locale
    ) {
        $this->menuBuilder = $menuBuilder;
        $this->routeBuilder = $routeBuilder;
        $this->em = $entityManager;
        $this->locale = $locale;
    }

    /**
     * @param StaticContent $page
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
                $route->setDefault('_controller', self::DEFAULT_CONTROLLER);
            }

            if ($auto && $needRoute && $route->getLocale() === $currentLocale) {
                $needRoute = false;
            }
        }

        if ($auto && $needRoute) {
            $route = $this->routeBuilder->createContentRoute(
                $this->fixStaticPrefix(null, $page),
                $page,
                self::DEFAULT_CONTROLLER
            );
            $route->setLocale($currentLocale);
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

    public function createPageCategory($title, $body, $path, $parentMenu, $locale = null)
    {
        $page = $this->createPage($title, $body, $path, $parentMenu, $locale, 'Positibe\Bundle\OrmContentBundle\Entity\CategoryContent');

        return $page;
    }

    public function createPage($title, $body, $path, $parentMenu, $locale = null, $class = null)
    {
        //Creating the Page
        $page = empty($class) ? new StaticContent() : new $class();
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
    public function createRoute($path, Page $page)
    {
        return $this->routeBuilder->createContentRoute($path, $page, self::DEFAULT_CONTROLLER);
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
            $menu = $this->menuBuilder->createContentMenuNode($page, $page->getTitle(), $parentMenu);

            if ($locale !== null) {
                $menu->setLocale($locale);
            }

            return $menu;
        }

        return null;
    }
} 
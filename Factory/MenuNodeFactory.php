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

use Gedmo\Sluggable\Util\Urlizer;
use Positibe\Bundle\OrmContentBundle\Entity\MenuNode;
use Positibe\Bundle\OrmMenuBundle\Menu\Factory\ContentAwareFactory;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeReferrersInterface;


/**
 * Class MenuNodeFactory
 * @package Positibe\Bundle\OrmContentBundle\Factory
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeFactory {
    /**
     * Create a Menu parent
     *
     * @param $name
     * @param array $ulAttr
     * @return MenuNode
     */
    public function createMenu($name, array $ulAttr = array())
    {
        $menu = new MenuNode();
        $menu->setName($name);
        $menu->setLinkType(ContentAwareFactory::LINK_TYPE_ROOT);
        $menu->setChildrenAttributes($ulAttr);

        return $menu;
    }

    /**
     * Create a simple menu node
     *
     * @param $name
     * @param $label
     * @param null $parentMenu
     * @return MenuNode
     */
    public function createSimpleMenuNode($name, $label, $parentMenu = null)
    {
        $menuNode = new MenuNode();
        $menuNode->setName($name);
        $menuNode->setLabel($label);

        $this->setParentMenu($menuNode, $parentMenu);

        return $menuNode;
    }

    /**
     * @param MenuNode $menuNode
     * @param MenuNode $parentMenu
     */
    protected function setParentMenu(MenuNode $menuNode, MenuNode $parentMenu = null)
    {
        if ($parentMenu !== null) {
            $menuNode->setParentObject($parentMenu);
            $parentMenu->addChild($menuNode);
        }
    }

    /**
     * Create a menu that reference to a content
     *
     * @param MenuNodeReferrersInterface $content
     * @param $label
     * @param null $parentMenu
     * @param int $position
     * @return MenuNode
     */
    public function createContentMenuNode(MenuNodeReferrersInterface $content, $label, $parentMenu = null, $position = 0)
    {
        $menu = new MenuNode();
        $menu->setLabel($label);
        $menu->setName(Urlizer::urlize($label));
        $menu->setLinkType(ContentAwareFactory::LINK_TYPE_CONTENT);
        $menu->setContent($content);
        $menu->setPosition($position);
        $this->setParentMenu($menu, $parentMenu);

        return $menu;
    }

    /**
     * Create a menu that reference to a route
     *
     * @param $label
     * @param string $route         The name of the route
     * @param null $parentMenu
     * @param int $position
     * @return MenuNode
     */
    public function createRouteMenuNode($label, $route, $parentMenu = null, $position = 0)
    {
        $menu = new MenuNode();
        $menu->setLabel($label);
        $menu->setName(Urlizer::urlize($label));
        $menu->setLinkType(ContentAwareFactory::LINK_TYPE_ROUTE);
        $menu->setRoute($route);
        $menu->setPosition($position);
        $this->setParentMenu($menu, $parentMenu);

        return $menu;
    }

    /**
     *  Create a menu that has a URI
     *
     * @param $label
     * @param $uri
     * @param null $parentMenu
     * @param int $position
     * @return MenuNode
     */
    public function createUriMenuNode($label, $uri, $parentMenu = null, $position = 0)
    {
        $menu = new MenuNode();
        $menu->setLabel($label);
        $menu->setName(Urlizer::urlize($label));
        $menu->setLinkType(ContentAwareFactory::LINK_TYPE_URI);
        $menu->setUri($uri);
        $menu->setPosition($position);
        $this->setParentMenu($menu, $parentMenu);

        return $menu;
    }
} 
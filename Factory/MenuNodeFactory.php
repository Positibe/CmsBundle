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
use Positibe\Bundle\CmsBundle\Entity\MenuNode;
use Positibe\Bundle\MenuBundle\Menu\Factory\ContentAwareFactory;
use Positibe\Bundle\MenuBundle\Model\ContentIdUtil;
use Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;


/**
 * Class MenuNodeFactory
 * @package Positibe\Bundle\CmsBundle\Factory
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeFactory implements FactoryInterface
{
    protected $em;
    protected $menuClass;

    public function __construct(EntityManager $entityManager, $menuClass)
    {
        $this->em = $entityManager;
        $this->menuClass = $menuClass;
    }

    /**
     * @return object|MenuNode
     */
    public function createNew()
    {
        return new MenuNode();
    }

    /**
     * @param $name
     * @return MenuNode
     */
    public function createNewByParentName($name)
    {
        $parent = $this->em->getRepository($this->menuClass)->findOneByName($name);

        $menu = $this->createNew();
        $menu->setParent($parent);

        return $menu;
    }

    /**
     * @param $parent
     * @param $name
     * @return MenuNode
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function createNewChildByParentName($parent, $name)
    {
        $parent = $this->em->getRepository($this->menuClass)->createQueryBuilder('o')
            ->join('o.parent', 'parent')
            ->where('o.name = :name AND parent.name = :parent')
            ->setParameter('name', $name)
            ->setParameter('parent', $parent)
            ->getQuery()->getOneOrNullResult();

        $menu = $this->createNew();
        $menu->setParent($parent);

        return $menu;
    }

    /**
     * @param $id
     * @return MenuNode
     */
    public function createNewByParentId($id)
    {
        $parent = $this->em->getRepository($this->menuClass)->find($id);

        $menu = new MenuNode();
        $menu->setParent($parent);

        return $menu;
    }

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
    public function createContentMenuNode(
        MenuNodeReferrersInterface $content,
        $label,
        $parentMenu = null,
        $position = 0
    ) {
        $menu = new MenuNode();
        $menu->setLabel($label);
        $menu->setName(Urlizer::urlize($label));
        $menu->setLinkType(ContentAwareFactory::LINK_TYPE_CONTENT);
        $menu->setContent($content);
        $menu->setContentId(ContentIdUtil::getContentId($content, $this->em));
        $menu->setPosition($position);
        $this->setParentMenu($menu, $parentMenu);

        return $menu;
    }

    /**
     * Create a menu that reference to a route
     *
     * @param $label
     * @param string $route The name of the route
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
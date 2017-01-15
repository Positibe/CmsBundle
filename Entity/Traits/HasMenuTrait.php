<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\Menu\NodeInterface;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\ContentBundle\Entity\MenuNode;

/**
 * Class HasMenuTrait
 * @package Positibe\Bundle\OrmMenuBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait HasMenuTrait
{
    /**
     * @var MenuNode[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\ContentBundle\Entity\MenuNode", cascade="persist", fetch="EXTRA_LAZY")
     */
    protected $menuNodes;

    /**
     * Get all menu nodes that point to this content.
     *
     * @return MenuNode[]|ArrayCollection Menu nodes that point to this content
     */
    public function getMenuNodes()
    {
        return $this->menuNodes;
    }

    /**
     * Add a menu node for this content.
     *
     * @param MenuNode|NodeInterface $menu
     * @return array|MenuNode
     */
    public function addMenuNode(NodeInterface $menu)
    {
        $this->menuNodes[] = $menu;

        return $this->menuNodes;
    }

    /**
     * Remove a menu node for this content.
     *
     * @param MenuNode|NodeInterface $menu
     * @return $this
     */
    public function removeMenuNode(NodeInterface $menu)
    {
        $this->menuNodes->removeElement($menu);

        return $this;
    }

    /**
     * @param $menus
     * @return $this|mixed
     */
    public function setMenuNodes($menus)
    {
        $this->menuNodes = $menus;

        return $this;
    }
}
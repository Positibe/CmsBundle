<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\OrmMenuBundle\Entity\MenuNodeBase;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Menu
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @ORM\Table(name="positibe_menu")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\OrmContentBundle\Entity\Repository\MenuNodeRepository")
 *
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNode extends MenuNodeBase
{
    /**
     * Parent menu node.
     *
     * @var MenuNode
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="MenuNode", inversedBy="children")
     */
    protected $parent;

    /**
     * @var MenuNode[]|Collection
     *
     * @ORM\OneToMany(targetEntity="MenuNode", mappedBy="parent", cascade="all")
     */
    protected $children;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmContentBundle\Entity\Page", inversedBy="menuNodes")
     */
    protected $page;

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        return array_merge(
            $options,
            array(
                'linkType' => $this->linkType,
                'content' => $this->page === null ? $this->getContent() : $this->getPage(),
                'contentClass' => $this->getContentClass(),
                'iconClass' => $this->getIconClass()
            )
        );
    }
} 
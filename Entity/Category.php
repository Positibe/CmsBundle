<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="positibe_page_category")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\CmsBundle\Repository\PageRepository")
 *
 * Class Category
 * @package Positibe\Bundle\CmsBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class Category extends Page
{
    /**
     * @var Page[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Page", mappedBy="category", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $children;

    public function __construct()
    {
        parent::__construct();
        $this->children = new ArrayCollection();
    }

    public function getType()
    {
        return 'Category';
    }

    public function addChild($page)
    {
        $this->children->add($page);

        return $this;
    }

    public function removeChild($page)
    {
        $this->children->removeElement($page);

        return $this;
    }

    /**
     * @return ArrayCollection|Page[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection|Page[] $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }
} 
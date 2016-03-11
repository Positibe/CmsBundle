<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Entity\Abstracts;

use Positibe\Bundle\OrmBlockBundle\Entity\Block;
use Positibe\Bundle\OrmContentBundle\Entity\Category;
use Positibe\Bundle\OrmContentBundle\Entity\Page;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractVisibilityBlock
 * @package Positibe\Bundle\OrmContentBundle\Entity\Abstracts
 *
 * @ORM\Entity(repositoryClass="Positibe\Bundle\OrmContentBundle\Entity\Repository\BlockRepository")
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class AbstractVisibilityBlock extends Block
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="always", type="boolean")
     */
    protected $always;

    /**
     * @var Category
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\OrmContentBundle\Entity\Category")
     * @ORM\JoinTable(name="positibe_block_visibility_category",
     *      joinColumns={@ORM\JoinColumn(name="block_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    protected $categories;

    /**
     * @var Page
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\OrmContentBundle\Entity\Page")
     * @ORM\JoinTable(name="positibe_block_visibility_pages",
     *      joinColumns={@ORM\JoinColumn(name="block_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")}
     *      )
     */
    protected $pages;

    /**
     * @var array
     *
     * @ORM\Column(name="routes", type="array")
     */
    protected $routes;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;

    /**
     * @return boolean
     */
    public function isAlways()
    {
        return $this->always;
    }

    /**
     * @param boolean $always
     */
    public function setAlways($always)
    {
        $this->always = $always;
    }

    /**
     * @return Category
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return Page
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param Page $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param array $routes
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }
} 
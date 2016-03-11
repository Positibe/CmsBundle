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

/**
 * Class AbstractVisibilityBlock
 * @package Positibe\Bundle\OrmContentBundle\Entity\Abstracts
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
abstract class AbstractVisibilityBlock extends Block {
    /**
     * @var Category
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\OrmContentBundle\Entity\Category")
     * @ORM\JoinTable(name="positibe_block_visibility_category")
     */
    protected $categories;

    /**
     * @var Page
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\OrmContentBundle\Entity\Page")
     * @ORM\JoinTable(name="positibe_block_visibility_pages")
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
     * @ORM\Column(name="routes", type="array")
     */
    protected $roles;

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
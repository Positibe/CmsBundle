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

use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishTimePeriodInterface;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Menu\NodeInterface;
use Positibe\Component\Publishable\Entity\PublishableTrait;
use Positibe\Component\Publishable\Entity\PublishTimePeriodTrait;
use Positibe\Component\Seo\Entity\SeoAwareEntityTrait;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeReferrersInterface;
use Positibe\Bundle\OrmMediaBundle\Entity\Media;
use Positibe\Bundle\OrmContentBundle\Entity\MenuNode;
use Positibe\Bundle\OrmRoutingBundle\Entity\Route;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class AbstractPage
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractPage implements PublishableInterface,
    PublishTimePeriodInterface,
    RouteReferrersInterface,
    MenuNodeReferrersInterface,
    SeoAwareInterface,
    TranslatableInterface
{
    use PublishableTrait;
    use PublishTimePeriodTrait;
    use SeoAwareEntityTrait;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"}, updatable=false, separator="_")
     * @ORM\Column(name="name", type="string", length=255, unique=TRUE)
     */
    protected $name;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255, nullable=TRUE)
     */
    protected $title;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="body", type="text", nullable=TRUE)
     */
    protected $body;

    /**
     * @var string
     *
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;
    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmMediaBundle\Entity\Media", cascade="all")
     */
    protected $image;

    /**
     * @var ArrayCollection|Route[]
     */
    protected $routes;

    /**
     * @var MenuNode[]|ArrayCollection
     */
    protected $menuNodes;

    /**
     * @var boolean
     *
     * @ORM\Column(name="featured", type="boolean")
     */
    protected $featured;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
        $this->menuNodes = new ArrayCollection();
        $this->seoMetadata = new SeoMetadata();
        $this->featured = false;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        if (!$this->seoMetadata->getTitle()) {
            $this->seoMetadata->setTitle($this->getTitle());
        }
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;

        if (!$this->seoMetadata->getMetaDescription() && $this->getBody()) {
            $description = substr(trim(strip_tags($this->getBody())), 0, 150);
            $this->seoMetadata->setMetaDescription($description);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function __toString()
    {
        return $this->title ? $this->title : $this->name;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

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
     * @param $menus
     * @return $this|mixed
     */
    public function setMenuNodes($menus)
    {
        $this->menuNodes = $menus;

        return $this;
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
     * @return ArrayCollection|Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param ArrayCollection|Route[] $routes
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * Add a route to the collection.
     *
     * @param Route $route
     * @return $this
     */
    public function addRoute($route)
    {
        $this->routes->add($route);
        $route->setContent($this);

        return $this;
    }

    /**
     * Remove a route from the collection.
     *
     * @param Route $route
     */
    public function removeRoute($route)
    {
        $this->routes->removeElement($route);
    }

    public function hasRoutes()
    {
        return count($this->routes) > 0;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return bool
     */
    public function getFeatured()
    {
        return $this->featured;
    }
    /**
     * @return boolean
     */
    public function isFeatured()
    {
        return $this->featured;
    }

    /**
     * @param boolean $featured
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }
} 
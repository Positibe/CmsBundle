<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Entity\Abstracts;

use Positibe\Bundle\ContentBundle\Model\ContentType;
use Positibe\Bundle\CmfRoutingExtraBundle\Model\CustomRouteInformation;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishTimePeriodInterface;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Menu\NodeInterface;
use Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface;
use Positibe\Bundle\MediaBundle\Entity\Media;
use Positibe\Bundle\ContentBundle\Entity\MenuNode;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class AbstractPage
 * @package Positibe\Bundle\ContentBundle\Entity
 * @ORM\Table(name="positibe_page")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\ContentBundle\Repository\PageRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\EntityListeners({"Positibe\Bundle\CmfRoutingExtraBundle\EventListener\RoutingAutoEntityListener"})
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\TranslationEntity(class="Positibe\Bundle\ContentBundle\Entity\PageTranslation")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
abstract class AbstractPage extends BaseContent implements
  ResourceInterface,
  PublishableInterface,
  PublishTimePeriodInterface,
  RouteReferrersInterface,
  MenuNodeReferrersInterface,
  SeoAwareInterface,
  TranslatableInterface,
  CustomRouteInformation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;
    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\MediaBundle\Entity\Media", cascade="all")
     */
    protected $image;

    /**
     * @var boolean
     *
     * @ORM\Column(name="featured", type="boolean")
     */
    protected $featured;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_controller", type="string", length=255, nullable=TRUE)
     */
    protected $customController;

    /**
     * @var string
     *
     * @ORM\Column(name="content_type", type="string", length=255, nullable=TRUE)
     */
    protected $contentType = ContentType::TYPE_PAGE;

    /**
     * @var ArrayCollection|RouteObjectInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route", orphanRemoval=TRUE, cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="positibe_page_routes",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="route_id", referencedColumnName="id", unique=true, onDelete="cascade")}
     * )
     */
    protected $routes;

    /**
     * @var MenuNode[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Positibe\Bundle\ContentBundle\Entity\MenuNode", cascade={"persist", "remove"}, fetch="EXTRA_LAZY", mappedBy="page")
     */
    protected $menuNodes;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
        $this->menuNodes = new ArrayCollection();
        $this->seoMetadata = new SeoMetadata();
        $this->featured = false;
        $this->publishStartDate = new \DateTime('now');
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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


    /**
     * @return string|null
     */
    public function getCustomController()
    {
        return $this->customController;
    }

    /**
     * @param string $customController
     */
    public function setCustomController($customController)
    {
        $this->customController = $customController;
    }

    public function getCustomTemplate()
    {
        return null;
    }

} 
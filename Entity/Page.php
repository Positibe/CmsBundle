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

use Doctrine\Common\Collections\ArrayCollection;
use Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractPage;
use Positibe\Bundle\OrmContentBundle\Model\ContentType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Positibe\Bundle\OrmRoutingBundle\Model\CustomRouteInformation;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

/**
 * Class Page
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @ORM\Table(name="positibe_page")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\OrmContentBundle\Entity\Repository\PageRepository")
 * @Gedmo\TranslationEntity(class="Positibe\Bundle\OrmContentBundle\Entity\PageTranslation")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\EntityListeners({"Positibe\Bundle\OrmRoutingBundle\EventListener\AutoRoutingEntityListener"})
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class Page extends AbstractPage implements CustomRouteInformation
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
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     */
    protected $parent;

    /**
     * @var Page[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent", cascade={"persist"})
     */
    protected $children;

    /**
     * @var ArrayCollection|RouteObjectInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\OrmRoutingBundle\Entity\Route", orphanRemoval=TRUE, cascade="all")
     * @ORM\JoinTable(name="positibe_page_routes")
     */
    protected $routes;

    /**
     * @var MenuNode[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MenuNode", mappedBy="page", cascade="all")
     */
    protected $menuNodes;

    public function __construct()
    {
        parent::__construct();
        $this->children = new ArrayCollection();
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

    public function getCategoryName()
    {
        return $this->parent === null ?: $this->parent->__toString();
    }

    /**
     * @return Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Page $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
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
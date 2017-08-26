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
use Positibe\Component\Publishable\Entity\PublishableTrait;
use Positibe\Component\Publishable\Entity\PublishTimePeriodTrait;
use Sonata\BlockBundle\Model\BlockInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Bundle\CoreBundle\Model\ChildInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishTimePeriodInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Block
 *
 * @ORM\Table(name="positibe_block")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\CmsBundle\Repository\BlockRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
class Block implements BlockInterface, PublishableInterface, PublishTimePeriodInterface, ChildInterface, ResourceInterface
{
    use PublishTimePeriodTrait;
    use PublishableTrait;

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
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="template_position", type="string", length=255, nullable=TRUE)
     */
    private $templatePosition;

    /**
     * @var boolean
     *
     * @ORM\Column(name="always", type="boolean")
     */
    protected $always = true;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     * @Gedmo\SortablePosition
     */
    protected $position = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="ttl", type="integer")
     */
    protected $ttl = 86400;

    /**
     * @var array
     *
     * @ORM\Column(name="settings", type="array")
     */
    protected $settings;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=TRUE)
     */
    protected $type;

    protected $parent;

    /**
     * @var Category
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\CmsBundle\Entity\Category", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="positibe_block_visibility_category",
     *      joinColumns={@ORM\JoinColumn(name="block_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    protected $categories;

    /**
     * @var Page
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\CmsBundle\Entity\Page", fetch="EXTRA_LAZY")
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

    public function __construct()
    {
        $this->settings = array();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * toString ...
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * Get id
     *
     * @return integer
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
     * Set name
     *
     * @param string $name
     * @return Block
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the template position
     *
     * @return string|void
     */
    public function getTemplatePosition()
    {
        return $this->templatePosition;
    }

    /**
     * @param string $templatePosition
     */
    public function setTemplatePosition($templatePosition)
    {
        $this->templatePosition = $templatePosition;
    }

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
     * Set ttl
     *
     * @param integer $ttl
     * @return Block
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Get ttl
     *
     * @return integer
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Set settings
     *
     * @param array $settings
     * @return Block
     */
    public function setSettings(array $settings = array())
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get settings
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Block
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Block
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }


    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function setEnabled($enabled)
    {
        $this->setPublishable($enabled);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEnabled()
    {
        return $this->isPublishable();
    }

    /**
     * {@inheritDoc}
     */
    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSetting($name, $default = null)
    {
        return isset($this->settings[$name]) ? $this->settings[$name] : $default;
    }

    protected function dashify($src)
    {
        return preg_replace('/[\/\.]/', '-', $src);
    }

    /**
     * @return string
     */
    public function getDashifiedId()
    {
        return $this->dashify($this->name);
    }

    /**
     * @return string
     */
    public function getDashifiedType()
    {
        return $this->dashify($this->getType());
    }


    /**
     * {@inheritDoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritDoc}
     *
     * Redirect to setParentObject
     */
    public function setParent(BlockInterface $parent = null)
    {
        return $this->setParentObject($parent);
    }

    /**
     * Set parent object regardless of its type. This can be a ContainerBlock
     * but also any other object.
     *
     * {@inheritDoc}
     */
    public function setParentObject($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get the parent object regardless of its type.
     *
     * {@inheritDoc}
     */
    public function getParentObject()
    {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
     *
     * Check if getParentObject is instanceof BlockInterface, otherwise return null
     */
    public function getParent()
    {
        if (($parent = $this->getParentObject()) instanceof BlockInterface) {
            return $parent;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function hasParent()
    {
        return ($this->getParentObject() instanceof BlockInterface);
    }

    /**
     * {@inheritDoc}
     */
    public function addChildren(BlockInterface $children)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        return new ArrayCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function hasChildren()
    {
        return false;
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

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
use Pcabreus\Utils\Html\NamedCharacterConverter;
use Positibe\Bundle\CmfRoutingExtraBundle\Entity\AutoRoute;
use Positibe\Component\Publishable\Entity\PublishTimePeriodTrait;
use Positibe\Component\Publishable\Entity\StatePublishableTrait;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishTimePeriodInterface;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class BaseContent
 * @package Positibe\Bundle\CmsBundle\Entity\Abstracts
 *
 * @ORM\MappedSuperclass
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class BaseContent implements
    PublishableInterface,
    PublishTimePeriodInterface,
    RouteReferrersInterface,
    SeoAwareInterface,
    TranslatableInterface
{
    use StatePublishableTrait;
    use PublishTimePeriodTrait;

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
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * @var ArrayCollection|RouteObjectInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\CmfRoutingExtraBundle\Entity\AutoRoute", orphanRemoval=TRUE, cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     *
     */
    protected $routes;

    /**
     * @var SeoMetadata
     *
     * @ORM\ManyToOne(targetEntity="Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata", cascade={"persist", "remove"})
     */
    protected $seoMetadata;


    public function __construct()
    {
        $this->routes = new ArrayCollection();
        $this->seoMetadata = new SeoMetadata();
        $this->publishStartDate = new \DateTime('now');
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
            $description = substr(NamedCharacterConverter::convert(trim(strip_tags($this->getBody()))), 0, 150);
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
        return $this->title ?: $this->name;
    }

    /**
     * @return ArrayCollection|AutoRoute[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param ArrayCollection|AutoRoute[] $routes
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * Add a route to the collection.
     *
     * @param AutoRoute $route
     * @return $this
     */
    public function addRoute($route)
    {
        $this->routes->add($route);

        return $this;
    }

    /**
     * Remove a route from the collection.
     *
     * @param AutoRoute $route
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
     * @return SeoMetadata
     */
    public function getSeoMetadata()
    {
        return $this->seoMetadata;
    }

    /**
     * @param $metadata
     * @return $this
     */
    public function setSeoMetadata($metadata)
    {
        $this->seoMetadata = $metadata;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
} 
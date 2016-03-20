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
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractPage;
use Positibe\Bundle\OrmContentBundle\Model\ContentType;

/**
 * @ORM\Table(name="positibe_page_category")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\OrmContentBundle\Entity\Repository\PageRepository")
 * @Gedmo\TranslationEntity(class="Positibe\Bundle\OrmContentBundle\Entity\PageTranslation")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * Class Category
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class Category extends AbstractPage
{
    /**
     * @var Page[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent", cascade={"persist"})
     */
    protected $children;

    public function __construct()
    {
        parent::__construct();
        $this->contentType = ContentType::TYPE_CATEGORY;
        $this->children = new ArrayCollection();
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
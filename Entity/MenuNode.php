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
use Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractPage;
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
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractPage")
     */
    protected $page;

    /**
     * @param \Positibe\Bundle\OrmMenuBundle\Model\MenuNodeReferrersInterface $content
     */
    public function setContent($content)
    {
        parent::setContent($content);

        if ($this->content instanceof AbstractPage) {
            $this->page = $content;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return array_merge(
            parent::getOptions(),
            array(
                'linkType' => $this->linkType,
                'content' => $this->getContent(),
                'contentClass' => $this->getContentClass(),
                'iconClass' => $this->getIconClass()
            )
        );
    }

    public function getContent()
    {
        if ($this->content == null && $this->page !== null) {
            $this->content = $this->page;
        }

        return $this->content;
    }

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
} 
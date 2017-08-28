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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\MenuBundle\Entity\MenuNodeBase;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class Menu
 * @package Positibe\Bundle\CmsBundle\Entity
 *
 * @ORM\Table(name="positibe_menu")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\CmsBundle\Repository\MenuNodeRepository")
 *
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNode extends MenuNodeBase implements ResourceInterface
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
     * @ORM\OneToMany(targetEntity="MenuNode", mappedBy="parent", cascade={"persist"}, orphanRemoval=TRUE, fetch="EXTRA_LAZY")
     */
    protected $children;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\CmsBundle\Entity\Page", inversedBy="menuNodes")
     */
    protected $page;

    /**
     * @param \Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface $content
     */
    public function setContent($content)
    {
        parent::setContent($content);

        if ($this->content instanceof Page) {
            $this->page = $content;
        } else {
            $this->page = null;
        }
    }

    public function getContentClass()
    {
        try {
            return trim(explode(':', $this->contentId)[0]);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setContentClass($class)
    {
        //this method is empty by choice, to prevent error on form and use the mapped = true
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
                'contentId' => $this->getContentId(),
                'iconClass' => $this->getIconClass(),
            )
        );
    }

    /**
     * @return Page
     */
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
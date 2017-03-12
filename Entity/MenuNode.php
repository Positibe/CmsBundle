<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\ContentBundle\Entity\Abstracts\AbstractPage;
use Positibe\Bundle\MenuBundle\Entity\MenuNodeBase;
use Gedmo\Mapping\Annotation as Gedmo;
use Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class Menu
 * @package Positibe\Bundle\ContentBundle\Entity
 *
 * @ORM\Table(name="positibe_menu")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\ContentBundle\Repository\MenuNodeRepository")
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
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\ContentBundle\Entity\Abstracts\AbstractPage", inversedBy="menuNodes")
     */
    protected $page;

    /**
     * @param \Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface $content
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

    /**
     * @return Page|AbstractPage|MenuNodeReferrersInterface
     */
    public function getContent()
    {
        if ($this->content == null && $this->page !== null) {
            $this->content = $this->page;
        }

        return $this->content;
    }

    /**
     * @return AbstractPage
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param AbstractPage $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }
} 
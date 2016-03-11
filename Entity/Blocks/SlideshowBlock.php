<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Entity\Blocks;

use Doctrine\Common\Collections\ArrayCollection;
use Positibe\Bundle\OrmBlockBundle\Entity\Block;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Table("positibe_block_slideshow")
 * @ORM\Entity
 *
 * Class SlideshowBlock
 * @package Positibe\Bundle\OrmMediaBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class SlideshowBlock extends Block
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
     * @var ContentBlock[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ContentBlock")
     * @ORM\JoinTable(name="positibe_block_slideshow_images")
     */
    protected $contentBlocks;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'positibe_orm_media.block_slideshow';
    }

    /**
     * @return ArrayCollection|ContentBlock[]
     */
    public function getContentBlocks()
    {
        return $this->contentBlocks;
    }

    /**
     * @param ArrayCollection|ContentBlock[] $contentBlocks
     */
    public function setContentBlocks($contentBlocks)
    {
        $this->contentBlocks = $contentBlocks;
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
} 
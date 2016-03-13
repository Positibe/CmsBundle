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

use Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractVisibilityBlock;
use Positibe\Bundle\OrmMediaBundle\Entity\Gallery;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="positibe_block_gallery")
 * @ORM\Entity
 *
 * Class GalleryBlock
 * @package Positibe\Bundle\OrmContentBundle\Entity\Blocks
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class GalleryBlock extends AbstractVisibilityBlock
{
    /**
     * @var Gallery
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmMediaBundle\Entity\Gallery", cascade="all")
     */
    protected $gallery;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'positibe_orm_content.block_gallery';
        $this->gallery = new Gallery();
    }

    /**
     * @param string $name
     * @return \Positibe\Bundle\OrmBlockBundle\Entity\Block|string
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->gallery->setName($name);

        return $name;
    }

    /**
     * @return Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param Gallery $gallery
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
    }
} 
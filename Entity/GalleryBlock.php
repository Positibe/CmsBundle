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

use Positibe\Bundle\MediaBundle\Entity\Gallery;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="positibe_block_gallery")
 * @ORM\Entity
 *
 * Class GalleryBlock
 * @package Positibe\Bundle\CmsBundle\Entity
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class GalleryBlock extends Block
{
    /**
     * @var Gallery
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\MediaBundle\Entity\Gallery", cascade="all")
     */
    protected $gallery;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'positibe_cms.block_gallery';
        $this->gallery = new Gallery();
    }

    /**
     * @param string $name
     * @return \Positibe\Bundle\CmsBundle\Entity\Block|string
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
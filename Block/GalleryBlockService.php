<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Block;

use Positibe\Bundle\OrmBlockBundle\Block\Service\AbstractBlockService;


/**
 * Class GalleryBlockService
 * @package Positibe\Bundle\OrmContentBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class GalleryBlockService extends AbstractBlockService
{
    protected $template = 'PositibeOrmContentBundle:Block:block_gallery.html.twig';
} 
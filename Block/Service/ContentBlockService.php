<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Block\Service;

use Positibe\Bundle\ContentBundle\Block\Service\AbstractBlockService;

/**
 * Class ContentBlockService
 * @package Positibe\Bundle\OrmMediaBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ContentBlockService extends AbstractBlockService
{
    protected $template = 'PositibeContentBundle:Block:block_content.html.twig';
} 
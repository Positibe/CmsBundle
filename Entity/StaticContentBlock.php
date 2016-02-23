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

use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\OrmBlockBundle\Entity\Block;

/**
 * @ORM\Table("positibe_block_static_content")
 * @ORM\Entity
 *
 * Class StaticContentBlock
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class StaticContentBlock extends Block {

    /**
     * @var StaticContent
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmContentBundle\Entity\StaticContent")
     */
    private $staticContent;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'positibe_orm_content.block_static_content';
    }

    public function getType()
    {
        return 'positibe_orm_content.block_static_content';
    }

    /**
     * @return mixed
     */
    public function getStaticContent()
    {
        return $this->staticContent;
    }

    /**
     * @param mixed $staticContent
     */
    public function setStaticContent($staticContent)
    {
        $this->staticContent = $staticContent;
    }


} 
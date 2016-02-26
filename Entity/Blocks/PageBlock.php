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

use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\OrmBlockBundle\Entity\Block;
use Positibe\Bundle\OrmContentBundle\Entity\Page;

/**
 * @ORM\Table("positibe_block_page")
 * @ORM\Entity
 *
 * Class PageBlock
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageBlock extends Block
{
    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmContentBundle\Entity\Page")
     */
    private $page;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'positibe_orm_content.block_page';
    }

    public function getType()
    {
        return 'positibe_orm_content.block_static_content';
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }


} 
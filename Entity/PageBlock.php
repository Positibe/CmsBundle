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

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("positibe_block_page")
 * @ORM\Entity
 *
 * Class PageBlock
 * @package Positibe\Bundle\CmsBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageBlock extends Block
{
    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\CmsBundle\Entity\Page")
     */
    private $page;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'positibe_cms.block_page';
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
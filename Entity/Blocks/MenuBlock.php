<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Entity\Blocks;

use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\ContentBundle\Entity\Abstracts\AbstractVisibilityBlock;
use Positibe\Bundle\ContentBundle\Entity\MenuNode;


/**
 * @ORM\Table("positibe_block_menu")
 * @ORM\Entity
 *
 * Class MenuBlock
 * @package Positibe\Bundle\CmfBundle\Block\Entity\Menu
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuBlock extends AbstractVisibilityBlock
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
     * @var MenuNode
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\ContentBundle\Entity\MenuNode")
     */
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'positibe_content.block_menu';
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param mixed $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }
} 
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

use Positibe\Bundle\OrmBlockBundle\Entity\Block;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\OrmContentBundle\Entity\MenuNode;


/**
 * @ORM\Table("positibe_block_menu")
 * @ORM\Entity
 *
 * Class MenuBlock
 * @package Positibe\Bundle\CmfBundle\Block\Entity\Menu
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuBlock extends Block
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
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmContentBundle\Entity\MenuNode")
     */
    private $menu;

    /**
     * @var string
     *
     * @ORM\Column(name="menu_template", type="string", length=255)
     */
    private $menuTemplate = null;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'positibe_cmf.block_menu';
        $this->menuTemplate = 'menu/main.html.twig';
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

    /**
     * @return string
     */
    public function getMenuTemplate()
    {
        return $this->menuTemplate;
    }

    /**
     * @param string $menuTemplate
     */
    public function setMenuTemplate($menuTemplate)
    {
        $this->menuTemplate = $menuTemplate;
    }
} 
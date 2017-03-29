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
 * @ORM\Table("positibe_block_menu")
 * @ORM\Entity
 *
 * Class MenuBlock
 * @package Positibe\Bundle\CoreBundle\Block\Entity\Menu
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
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\CmsBundle\Entity\MenuNode")
     */
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'positibe_cms.block_menu';
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
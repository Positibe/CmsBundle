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

use Doctrine\Common\Collections\ArrayCollection;
use Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractPage;
use Positibe\Bundle\OrmContentBundle\Model\ContentType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Positibe\Bundle\OrmRoutingBundle\Model\CustomRouteInformation;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

/**
 * Class Page
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @ORM\Table(name="positibe_page")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\OrmContentBundle\Entity\Repository\PageRepository")
 *
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class Page extends AbstractPage
{
    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     */
    protected $parent;

    public function getCategoryName()
    {
        return $this->parent === null ?: $this->parent->__toString();
    }

    /**
     * @return Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Page $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
} 
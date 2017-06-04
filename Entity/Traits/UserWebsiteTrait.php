<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Positibe\Bundle\CmsBundle\Entity\Website;

/**
 * Class UserWebsiteTrait
 * @package Positibe\Bundle\CmsBundle\Entity\Traits
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait UserWebsiteTrait
{
    /**
     * @var Website[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\CmsBundle\Entity\Website")
     * @ORM\JoinTable(name="app_user_website")
     */
    protected $websites;

    /**
     * @return Website[]|ArrayCollection
     */
    public function getWebsites()
    {
        return $this->websites;
    }

    /**
     * @param Website[]|ArrayCollection $websites
     */
    public function setWebsites($websites)
    {
        $this->websites = $websites;
    }

    /**
     * All websites plus the `null` value
     *
     * @return array
     */
    public function getHosts()
    {
        return array_merge($this->getWebsites()->toArray(), [null]);
    }
}
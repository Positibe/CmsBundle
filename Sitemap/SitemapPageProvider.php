<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Sitemap;

use Doctrine\ORM\EntityManager;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderInterface;
use Symfony\Component\Routing\Route;

/**
 * Class SitemapPageProvider
 * @package Positibe\Bundle\CmsBundle\Sitemap
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class SitemapPageProvider implements LoaderInterface
{
    protected $manager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->manager = $entityManager;
    }

    /**
     * @param string $sitemap the name of the sitemap
     *
     * @return Route[]
     */
    public function load($sitemap)
    {
        return $this->manager->getRepository('PositibeCmsBundle:Page')->findAll();
    }

}
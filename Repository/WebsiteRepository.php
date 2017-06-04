<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Repository;

use Positibe\Bundle\CoreBundle\Repository\EntityRepository;

/**
 * Class WebsiteRepository
 * @package Positibe\Bundle\CmsBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class WebsiteRepository extends EntityRepository
{
    public function getEnabled()
    {
        return $this->createQueryBuilder('o')
            ->where('o.enabled = :true')
            ->indexBy('o', 'o.domain')
            ->setParameter('true', true)
            ->getQuery()->getResult();
    }
}

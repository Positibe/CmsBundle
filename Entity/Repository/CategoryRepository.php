<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Entity\Repository;

/**
 * Class CategoryRepository
 * @package Positibe\Bundle\OrmContentBundle\Entity\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class CategoryRepository extends PageRepository
{
    public function findOneByRoutes($route)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('seo', 'r', 'image', 'children', 'cimage', 'routes')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.seoMetadata', 'seo')
            ->leftJoin('o.children', 'children')
            ->leftJoin('children.image', 'cimage')
            ->leftJoin('children.routes', 'routes')
            ->join('o.routes', 'r')
            ->where('r = :route')
            ->setParameter('route', $route);


        return $this->getQuery($qb)->getOneOrNullResult();
    }

} 
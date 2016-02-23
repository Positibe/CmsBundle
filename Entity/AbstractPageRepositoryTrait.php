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

use Doctrine\ORM\Query;
use Positibe\Bundle\OrmMenuBundle\Entity\MenuNode;


/**
 * Class AbstractPageRepositoryTrait
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait AbstractPageRepositoryTrait
{
    public function findOneByMenuNodes(MenuNode $menuNode)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('routes')
            ->join('o.menuNodes', 'm')
            ->leftJoin('o.routes', 'routes')
            ->where('m = :menu')
            ->setParameter('menu', $menuNode);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param $route
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByRoutes($route)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('seo', 'image')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.seoMetadata', 'seo')
            ->join('o.routes', 'r')
            ->where('r = :route')
            ->setParameter('route', $route);

        $query = $qb->getQuery()->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getOneOrNullResult();
    }

    public function findByFeatured()
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('seo', 'image')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.seoMetadata', 'seo')
            ->join('o.routes', 'r')
            ->where('o.featured = :featured')
            ->orderBy('o.publishStartDate', 'DESC')
            ->setParameter('featured', true);

        $query = $qb->getQuery()->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getResult();
    }
} 
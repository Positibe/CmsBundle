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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Positibe\Bundle\OrmContentBundle\Entity\Traits\PageRepositoryTrait;
use Positibe\Bundle\OrmMenuBundle\Entity\HasMenuRepositoryInterface;
use Positibe\Bundle\OrmRoutingBundle\Entity\HasRoutesRepositoryInterface;

/**
 * Class PageRepository
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageRepository extends EntityRepository implements HasRoutesRepositoryInterface, HasMenuRepositoryInterface
{
    use PageRepositoryTrait;

    public function findOneByRoutes($route)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('seo', 'children', 'image', 'cimage', 'routes')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.seoMetadata', 'seo')
            ->leftJoin('o.children', 'children')
            ->leftJoin('children.image', 'cimage')
            ->leftJoin('children.routes', 'routes')
            ->join('o.routes', 'r')
            ->where('r = :route')
            ->setParameter('route', $route);

        $query = $qb->getQuery()->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getOneOrNullResult();
    }

} 
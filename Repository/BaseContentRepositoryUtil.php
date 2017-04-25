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

use Doctrine\ORM\QueryBuilder;

/**
 * Class BaseContentRepositoryTraits
 * @package Positibe\Bundle\CmsBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class BaseContentRepositoryUtil
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     */
    public static function canPublishOnDate(QueryBuilder $queryBuilder, array &$criteria = [])
    {
        if (!empty($criteria['can_publish_on_date'])) {
            $queryBuilder
                ->andWhere(
                    '(o.publishStartDate <= :can_publish_on_date OR o.publishStartDate IS NULL) AND (o.publishEndDate >= :can_publish_on_date OR o.publishEndDate IS NULL) AND o.state = :state'
                )
                ->setParameter('can_publish_on_date', $criteria['can_publish_on_date'])
                ->setParameter('state', 'published');
            unset($criteria['can_publish_on_date']);
            unset($criteria['state']);
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public static function joinRoutes(QueryBuilder $queryBuilder)
    {
        $queryBuilder->join('o.routes', 'routes')->addSelect('routes');
    }
}
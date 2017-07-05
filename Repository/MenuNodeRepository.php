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

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Positibe\Bundle\CmsBundle\Entity\MenuNode;
use Positibe\Bundle\CoreBundle\Repository\EntityRepository;
use Positibe\Bundle\CoreBundle\Repository\LocaleRepositoryTrait;
use Positibe\Bundle\MenuBundle\Repository\MenuNodeRepositoryInterface;
use Positibe\Bundle\MenuBundle\Repository\MenuNodeRepositoryTrait;
use Positibe\Bundle\MenuBundle\Model\MenuNodeInterface;


/**
 * Class MenuNodeRepository
 * @package Positibe\Bundle\CmsBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeRepository extends EntityRepository implements MenuNodeRepositoryInterface
{
    use MenuNodeRepositoryTrait;
    use LocaleRepositoryTrait;

    /**
     * @param $name
     * @param int $level
     * @return MenuNode
     * @throws \Doctrine\ORM\NonUniqueResultException|MenuNodeInterface
     */
    public function findOneByName($name, $level = 1)
    {
        $alias = 'mc';
        $qb = $this->createFindOneByName($name, $level, $alias);

        return $this->getQuery($qb)->getOneOrNullResult();
    }

    /**
     * @param $name
     * @param $parent
     * @param int $level
     * @return MenuNode
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByNameAndParent($name, $parent, $level = 1)
    {
        $alias = 'mc';

        $qb = $this->createFindOneByName($name, $level, $alias);

        $qb->join('m.parent', 'p')
          ->andWhere('p.name = :parent_name')
          ->setParameter(
            'parent_name',
            $parent
          );

        return $this->getQuery($qb)->getOneOrNullResult();
    }

    /**
     * @param $name
     * @param $level
     * @param $alias
     * @return QueryBuilder
     */
    public function createFindOneByName($name, $level, $alias)
    {
        $qb = $this->createQueryBuilder('m')->where('m.name = :name')->setParameter('name', $name)
          ->leftJoin('m.children', $alias)
          ->leftJoin($alias . '.page', 'page_' . $alias)
          ->leftJoin('page_' . $alias . '.routes', 'routes_page_' . $alias)
          ->addSelect($alias, 'page_' . $alias, 'routes_page_' . $alias)
          ->orderBy($alias . '.position', 'ASC');

        while ($level > 0) {
            $nextAlias = $alias . $level;
            $qb->addSelect($nextAlias, 'page_' . $nextAlias)
              ->leftJoin($alias . '.children', $nextAlias)
              ->leftJoin($nextAlias . '.page', 'page_' . $nextAlias);
            $alias = $nextAlias;
            $level--;
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (isset($criteria['parent'])) {
            $queryBuilder
              ->join('o.parent', 'p')
              ->andWhere('p.name = :parent_name')
              ->setParameter(
                'parent_name',
                $criteria['parent']
              );
            unset($criteria['parent']);
        }
        parent::applyCriteria($queryBuilder, $criteria);
    }
} 
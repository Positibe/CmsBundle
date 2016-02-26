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
use Positibe\Bundle\OrmMenuBundle\Entity\MenuNodeRepositoryInterface;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeInterface;


/**
 * Class MenuNodeRepository
 * @package Positibe\Bundle\OrmContentBundle\Entity\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeRepository extends EntityRepository implements MenuNodeRepositoryInterface {
    /**
     * @param $name
     * @param int $level
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException|MenuNodeInterface
     */
    public function findOneByName($name, $level = 1)
    {
        $alias = 'mc';
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

        $query = $qb->getQuery();

//        $query->setHint(
//            Query::HINT_CUSTOM_OUTPUT_WALKER,
//            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
//        );

        return $query->getOneOrNullResult();
    }
} 
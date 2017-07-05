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
use Positibe\Bundle\CoreBundle\Repository\EntityRepository;
use Positibe\Bundle\CoreBundle\Repository\LocaleRepositoryTrait;
use Positibe\Bundle\MenuBundle\Repository\HasMenuRepositoryInterface;
use Positibe\Bundle\MenuBundle\Model\MenuNodeInterface;

/**
 * Class PageRepository
 * @package Positibe\Bundle\CmsBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageRepository extends EntityRepository implements HasMenuRepositoryInterface
{
    use LocaleRepositoryTrait;

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param string $alias
     * @param string $indexBy The index for the from.
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias, $indexBy = null)
    {
        $qb = parent::createQueryBuilder($alias, $indexBy);

        $qb->andWhere($alias.' INSTANCE OF '.$this->_entityName);

        return $qb;
    }

    public function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (!empty($criteria['category'])) {
            $queryBuilder
                ->leftJoin('o.category', 'category')
                ->andWhere('category.name = :category')
                ->setParameter('category', $criteria['category']);
            unset($criteria['category']);
        }

        BaseContentRepositoryUtil::canPublishOnDate($queryBuilder, $criteria);
        if ($this->locale) {
            BaseContentRepositoryUtil::joinRoutes($queryBuilder, $this->locale);
        }

        parent::applyCriteria($queryBuilder, $criteria);

        return $queryBuilder;
    }

    public function findOneByMenuNodes(MenuNodeInterface $menuNode)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('routes')
            ->join('o.menuNodes', 'm')
            ->leftJoin('o.routes', 'routes')
            ->where('m = :menu')
            ->setParameter('menu', $menuNode);

        $query = $this->getQuery($qb);

        return $query->getOneOrNullResult();
    }

    public function findOneByMenuNodesName($menuNodeName)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('routes')
            ->join('o.menuNodes', 'm')
            ->leftJoin('o.routes', 'routes')
            ->where('m.name = :menu')
            ->setParameter('menu', $menuNodeName);

        $query = $this->getQuery($qb);

        return $query->getOneOrNullResult();
    }

    public function findByFeatured()
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('seo', 'image', 'r')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.seoMetadata', 'seo')
            ->join('o.routes', 'r')
            ->where('o.featured = :featured')
            ->orderBy('o.publishStartDate', 'DESC')
            ->setParameter('featured', true);

        $query = $this->getQuery($qb);

        return $query->getResult();
    }


    public function findOneByRoutes($route)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('seo', 'image', 'r')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.seoMetadata', 'seo')
            ->join('o.routes', 'r')
            ->where('r = :route')
            ->setParameter('route', $route);

        $query = $this->getQuery($qb);

        return $query->getOneOrNullResult();
    }

    public function findContentByCategory($category, $count = 2, $sort = 'publishStartDate', $order = 'DESC')
    {
        $qb = $this->createQueryBuilder('o')
//          ->addSelect('p', 'routes', 'image')
//          ->leftJoin('o.routes', 'routes')
//          ->leftJoin('o.image', 'image')
            ->innerJoin('o.category', 'c');
        if (is_string($category)) {
            $qb->where('c.name = :category');
        } else {
            $qb->where('c = :category');
        }
        $qb->setParameter('category', $category)->setMaxResults($count)->orderBy(sprintf('o.%s', $sort), $order);

        return $this->getQuery($qb)->getResult();
    }

    public function findOneContent($content)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('image', 'routes')
            ->where('o = :content')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.routes', 'routes')
            ->setParameter('content', $content);

        return $this->getQuery($qb)->getOneOrNullResult();
    }

    public function findOneByName($name)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('image', 'routes')
            ->where('o.name = :name')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.routes', 'routes')
            ->setParameter('name', $name);

        return $this->getQuery($qb)->getOneOrNullResult();
    }

    public function find($id)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('image', 'routes', 'seo')
            ->where('o.id = :id')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.routes', 'routes')
            ->leftJoin('o.seoMetadata', 'seo')
            ->setParameter('id', $id);

        return $this->getQuery($qb)->getOneOrNullResult();
    }
}
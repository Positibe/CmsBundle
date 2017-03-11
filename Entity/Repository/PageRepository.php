<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Entity\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Positibe\Bundle\CoreBundle\Repository\LocaleRepositoryTrait;
use Positibe\Bundle\OrmMenuBundle\Entity\HasMenuRepositoryInterface;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeInterface;
use Positibe\Bundle\OrmRoutingBundle\Entity\HasRoutesRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Class PageRepository
 * @package Positibe\Bundle\ContentBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageRepository extends EntityRepository implements HasMenuRepositoryInterface, HasRoutesRepositoryInterface
{
    use LocaleRepositoryTrait;

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
            ->addSelect('seo', 'image')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.seoMetadata', 'seo')
            ->join('o.routes', 'r')
            ->where('o.featured = :featured')
            ->orderBy('o.publishStartDate', 'DESC')
            ->setParameter('featured', true);

        $query = $this->getQuery($qb);

        return $query->getResult();
    }

    public function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (!empty($criteria['title'])) {
            $queryBuilder
                ->andWhere('o.title LIKE :title')
                ->setParameter('title', '%'.$criteria['title'].'%');
            unset($criteria['title']);
        }

        if (!empty($criteria['body'])) {
            $queryBuilder
                ->andWhere('o.body LIKE :body')
                ->setParameter('body', '%'.$criteria['body'].'%');
            unset($criteria['body']);
        }

        if (!empty($criteria['category'])) {
            $queryBuilder
                ->leftJoin('o.parent', 'category')
                ->andWhere('category.name = :category')
                ->setParameter('category', $criteria['category']);
            unset($criteria['category']);
        }

        if (!empty($criteria['publish_start_since'])) {
            $queryBuilder
                ->andWhere('o.publishStartDate > :publish_start_since')
                ->setParameter('publish_start_since', $criteria['publish_start_since']);
            unset($criteria['publish_start_since']);
        }

        parent::applyCriteria($queryBuilder, $criteria);

        return $queryBuilder;
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

    public function findContentByParent($parent, $count = 2, $sort = 'publishStartDate', $order = 'DESC')
    {
        $qb = $this->createQueryBuilder('o')
//          ->addSelect('p', 'routes', 'image')
//          ->leftJoin('o.routes', 'routes')
//          ->leftJoin('o.image', 'image')
            ->innerJoin('o.parent', 'p');
        if (is_string($parent)) {
            $qb->where('p.name = :parent');
        } else {
            $qb->where('p = :parent');
        }
        $qb->setParameter('parent', $parent)
            ->setMaxResults($count)
            ->orderBy(sprintf('o.%s', $sort), $order);

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
}
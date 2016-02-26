<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Positibe\Bundle\OrmContentBundle\Entity\Page;

/**
 * Class PageRepositoryTrait
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait PageRepositoryTrait
{
    use AbstractPageRepositoryTrait;

    public function createPaginator(array $criteria = array(), array $sorting = array())
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder = $this->filter($queryBuilder, $criteria, $sorting);

        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, true, null));
    }

    public function filter(QueryBuilder $queryBuilder, array $criteria = array(), array $sorting = array())
    {
        if (!empty($criteria['title'])) {
            $queryBuilder
                ->andWhere('o.title LIKE :title')
                ->setParameter('title', '%' . $criteria['title'] . '%');
            unset($criteria['title']);
        }

        if (!empty($criteria['body'])) {
            $queryBuilder
                ->andWhere('o.body LIKE :body')
                ->setParameter('body', '%' . $criteria['body'] . '%');
            unset($criteria['body']);
        }

        if (!empty($criteria['category'])) {
            $queryBuilder
                ->leftJoin('o.parent','category')
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

        if (method_exists($this, 'applyCriteria')) {
            $this->applyCriteria($queryBuilder, $criteria);
        }

        if (method_exists($this, 'applySorting')) {
            $this->applySorting($queryBuilder, $sorting);
        }

        return $queryBuilder;
    }

    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        foreach ($criteria as $property => $value) {
            $name = $this->getPropertyName($property);
            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($name, ':' . $parameter))
                    ->setParameter($parameter, $value);
            }
        }
    }

    protected function getPropertyName($name)
    {
        if (false === strpos($name, '.')) {
            return $this->getAlias() . '.' . $name;
        }

        return $name;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $sorting
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = array())
    {
        foreach ($sorting as $property => $order) {
            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    public function findFiltered(array $criteria = array(), array $sorting = array())
    {
        $qb = $this->createQueryBuilder('o');

        $qb = $this->filter($qb, $criteria, $sorting);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $contentType
     * @param $locale
     * @return Page[]|ArrayCollection
     */
    public function findByContentType($contentType, $locale)
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.contentType = :contentType')
            ->setParameter('contentType', $contentType);
        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findContentByParent($parent, $count = 2, $sort = 'publishStartDate', $order = 'DESC')
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('p', 'routes')
            ->leftJoin('o.routes', 'routes')
            ->innerJoin('o.parent', 'p')
            ->where('p = :parent')
            ->setParameter('parent', $parent)
            ->setMaxResults($count)
            ->orderBy(sprintf('o.%s', $sort), $order);
        $query = $qb->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getResult();
    }

    public function findOneContent($content)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('image', 'routes')
            ->where('o = :content')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.routes', 'routes')
            ->setParameter('content', $content);

        $query = $qb->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getOneOrNullResult();
    }

    public function findOneByName($name)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('image', 'routes')
            ->where('o.name = :name')
            ->leftJoin('o.image', 'image')
            ->leftJoin('o.routes', 'routes')
            ->setParameter('name', $name);

        $query = $qb->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getOneOrNullResult();

    }

    public function getAlias()
    {
        return 'o';
    }
} 
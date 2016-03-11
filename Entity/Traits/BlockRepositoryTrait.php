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

use Doctrine\ORM\QueryBuilder;
use Positibe\Bundle\OrmContentBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class BlockRepositoryTrait
 * @package Positibe\Bundle\OrmContentBundle\Entity\Traits
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait BlockRepositoryTrait
{
    public function findByTemplatePosition($configuration)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('o')
            ->orderBy('o.position', 'ASC')
            ->orderBy('o.updatedAt', 'DESC');

        if (isset($configuration['template_position'])) {
            $qb->where('o.templatePosition = :templatePosition')->setParameter(
                'templatePosition',
                $configuration['template_position']
            );
        }

        if (isset($configuration['request'])) {
            /** @var Request $request */
            $request = $configuration['request'];
            $uri = $request->getUri();
            $route = $request->get('_route');
            $content = $request->get('contentDocument');
            $category = null;
            if ($content instanceof Page) {
                $category = $content->getParent();
            }
            $qb
                ->leftJoin('o.pages', 'pages')
                ->leftJoin('o.categories', 'categories')
                ->andWhere('o.always = :always OR pages = :page OR categories = :category OR o.routes LIKE :route')
                ->setParameter('always', true)
                ->setParameter('page', $content)
                ->setParameter('category', $category)
                ->setParameter('route', '%' . $route . '%');
        }

        return $qb->getQuery()->getResult();
    }
} 
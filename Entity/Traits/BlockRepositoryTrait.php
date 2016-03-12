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

use Positibe\Bundle\OrmContentBundle\Entity\Page;

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
            $request = $configuration['request'];
            $route = $request->get('_route');
            $stringQuery = 'o.always = :always OR o.routes LIKE :route';
            if ($content = $request->get('contentDocument')) {
                $stringQuery .= ' OR pages = :page';
                $qb->leftJoin('o.pages', 'pages')->setParameter('page', $content);

                if ($content instanceof Page) {
                    $stringQuery .= ' OR categories = :category';
                    $qb->leftJoin('o.categories', 'categories')->setParameter('category', $content->getParent());
                }
            }
            $qb
                ->andWhere($stringQuery)
                ->setParameter('always', true)
                ->setParameter('route', '%' . $route . '%');
        }

        return $qb->getQuery()->getResult();
    }
} 
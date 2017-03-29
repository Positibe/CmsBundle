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

use Positibe\Bundle\CmsBundle\Entity\Page;
use Positibe\Bundle\CoreBundle\Repository\LocaleRepositoryTrait;
use Positibe\Bundle\CoreBundle\Repository\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class BlockRepository
 * @package Positibe\Bundle\CmsBundle\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class BlockRepository extends EntityRepository
{
    use LocaleRepositoryTrait;

    public function findByTemplatePosition($configuration, Request $request = null)
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
        /** @var Request $request */
        if ($request = isset($configuration['request']) ? $configuration['request'] : $request) {
            $route = $request->get('_route');
            $stringQuery = 'o.always = :always OR o.routes LIKE :route';
            if ($content = $request->get('contentDocument')) {
                $stringQuery .= ' OR pages = :page';
                $qb->leftJoin('o.pages', 'pages')->setParameter('page', $content);

                if ($content instanceof Page) {
                    $stringQuery .= ' OR categories = :category';
                    $qb->leftJoin('o.categories', 'categories')->setParameter('category', $content->getCategory());
                }
            }
            $qb
                ->andWhere($stringQuery)
                ->setParameter('always', true)
                ->setParameter('route', '%'.$route.'%');
        }
        $query = $this->getQuery($qb);

        return $query->getResult();
    }
} 
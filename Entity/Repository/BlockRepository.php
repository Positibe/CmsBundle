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

use Positibe\Bundle\CmfBundle\Repository\LocaleRepositoryTrait;
use Positibe\Bundle\OrmBlockBundle\Entity\BlockRepositoryInterface;
use Positibe\Bundle\OrmContentBundle\Entity\Page;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class BlockRepository
 * @package Positibe\Bundle\OrmContentBundle\Entity\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class BlockRepository extends EntityRepository implements BlockRepositoryInterface
{
    use LocaleRepositoryTrait;

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
            /** @var Request $request */
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
        $query = $this->getQuery($qb);


        return $query->getResult();
    }
} 
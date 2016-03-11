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
use Positibe\Bundle\OrmBlockBundle\Entity\BlockRepositoryInterface;
use Positibe\Bundle\OrmContentBundle\Entity\Traits\BlockRepositoryTrait;


/**
 * Class BlockRepository
 * @package Positibe\Bundle\OrmContentBundle\Entity\Repository
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class BlockRepository extends EntityRepository implements BlockRepositoryInterface
{
    use BlockRepositoryTrait;
} 
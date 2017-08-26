<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Doctrine\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;


/**
 * Class WebsiteEntityListener
 * @package Positibe\Bundle\CmsBundle\Doctrine\EventListener
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class WebsiteEntityListener
{
    protected $entities;

    /**
     * @return mixed
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param mixed $entities
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    public function preUpdate($entity, PreUpdateEventArgs $event)
    {
        if ($event->hasChangedField('domain')) {
            $old = $event->getOldValue('domain');
            $new = $event->getnewValue('domain');
            $manager = $event->getEntityManager();
            foreach ($this->entities as $entity) {
                $manager->createQuery(
                    sprintf("UPDATE %s u SET u.host = '%s' WHERE u.host = '%s'", $entity, $new, $old)
                )->getResult();
            }
        }
    }
}
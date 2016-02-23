<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Positibe\Bundle\OrmContentBundle\Model\ContentType;

/**
 * @ORM\Table(name="positibe_static_content")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\OrmContentBundle\Entity\StaticContentRepository")
 * @Gedmo\TranslationEntity(class="Positibe\Bundle\OrmContentBundle\Entity\StaticContentTranslation")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 * Class StaticContent
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class StaticContent extends Page
{
    public function __construct()
    {
        parent::__construct();
        $this->setContentType(ContentType::TYPE_PAGE);
    }
} 
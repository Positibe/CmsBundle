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

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class StaticContentTranslation
 * @package Positibe\Bundle\OrmContentBundle\Entity
 *
 * @ORM\Table(name="positibe_static_content_translations", indexes={
 *      @ORM\Index(name="positibe_static_content_translation_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class StaticContentTranslation extends AbstractTranslation {

} 
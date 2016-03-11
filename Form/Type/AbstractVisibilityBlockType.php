<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class AbstractVisibilityBlockType
 * @package Positibe\Bundle\OrmContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class AbstractVisibilityBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'categories',
                null,
                array(
                    'label' => 'visibility_block.form.categories_label',
                    'multiple' => true
                )
            )
            ->add(
                'pages',
                null,
                array(
                    'label' => 'visibility_block.form.pages_label',
                    'multiple' => true
                )
            )
            ->add(
                'routes',
                null,
                array(
                    'label' => 'visibility_block.form.routes_label'
                )
            )
            ->add(
                'roles',
                null,
                array(
                    'label' => 'visibility_block.form.roles_label'
                )
            );
    }

    public function getParent()
    {
        return 'positibe_abstract_block';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractVisibilityBlock',
                'translation_domain' => 'PositibeOrmContentBundle'
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'positibe_block_visibility';
    }

} 
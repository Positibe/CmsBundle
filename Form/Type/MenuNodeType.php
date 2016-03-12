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
 * Class MenuNodeType
 * @package Positibe\Bundle\OrmContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'label',
            null,
            array(
                'label' => 'menu_node.form.label_label',
                'translation_domain' => 'PositibeOrmMenuBundle'
            )
        )
            ->add(
                'parent',
                null,
                array(
                    'label' => 'menu_node.form.parent_label',
                    'translation_domain' => 'PositibeOrmMenuBundle'
                )
            )
            ->add(
                'display',
                null,
                array(
                    'label' => 'menu_node.form.display_label',
                    'data' => true,
                    'required' => false
                )
            );


        $builder->add('locale', 'hidden');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\OrmMenuBundle\Entity\MenuNode',
                'translation_domain' => 'PositibeOrmMenuBundle'
            )
        );
    }

    public function getParent()
    {
        return 'positibe_block_visibility';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'positibe_content_menu';
    }
} 
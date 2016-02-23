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

use Positibe\Bundle\OrmContentBundle\Entity\StaticContentBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class StaticContentBlockType
 * @package Positibe\Bundle\OrmContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class StaticContentBlockType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'staticContent',
                'genemu_jquerychosen_entity',
                array(
                    'class' => 'Positibe\Bundle\OrmContentBundle\Entity\StaticContent',
                    'attr' => array('class' => 'chosen-select form-control'),
                    'required' => true,
                    'label' => 'static_content_block.form.static_content_label',
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\OrmContentBundle\Entity\StaticContentBlock',
                'translation_domain' => 'PositibeOrmContentBundle'
            )
        );
    }

    public function getParent()
    {
        return 'positibe_abstract_block';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'positibe_static_content_block';
    }

} 
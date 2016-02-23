<?php

namespace Positibe\Bundle\OrmContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CategoryContentType
 * @package Positibe\Bundle\OrmContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class CategoryContentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove(
                'parent'
            )
            ->remove(
                'contentType'
            )
            ->remove(
                'featured'
            );
    }

    public function getParent()
    {
        return 'positibe_static_content';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\OrmContentBundle\Entity\CategoryContent',
                'translation_domain' => 'PositibeOrmContentBundle'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'positibe_category_content';
    }
}

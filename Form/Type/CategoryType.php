<?php

namespace Positibe\Bundle\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CategoryType
 * @package Positibe\Bundle\ContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class CategoryType extends AbstractType
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
        return 'positibe_page';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\ContentBundle\Entity\Category',
                'translation_domain' => 'PositibeContentBundle'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'positibe_category';
    }
}

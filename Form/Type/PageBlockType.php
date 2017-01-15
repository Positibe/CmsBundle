<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PageBlockType
 * @package Positibe\Bundle\ContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageBlockType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'page',
                'entity',
                array(
                    'class' => 'Positibe\Bundle\ContentBundle\Entity\Page',
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
                'data_class' => 'Positibe\Bundle\ContentBundle\Entity\Blocks\PageBlock',
                'translation_domain' => 'PositibeContentBundle'
            )
        );
    }

    public function getParent()
    {
        return 'positibe_block_visibility';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'positibe_page_block';
    }

} 
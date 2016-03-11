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

use Positibe\Bundle\OrmMediaBundle\Entity\MediaBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class SlideshowBlockType
 * @package Positibe\Bundle\OrmContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class SlideshowBlockType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'contentBlocks',
                'entity',
                array(
                    'class' => 'Positibe\Bundle\OrmContentBundle\Entity\Blocks\ContentBlock',
                    'required' => false,
                    'label' => 'slideshow_block.form.image_blocks_label',
                    'translation_domain' => 'PositibeOrmMediaBundle',
                    'multiple' => true,
                    'expanded' => true
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
                'data_class' => 'Positibe\Bundle\OrmContentBundle\Entity\Blocks\SlideshowBlock',
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
        return 'positibe_slideshow_block';
    }

} 
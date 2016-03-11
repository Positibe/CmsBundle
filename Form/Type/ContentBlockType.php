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
 * Class ContentBlockType
 * @package Positibe\Bundle\OrmContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ContentBlockType extends AbstractType
{
    private $locales;

    public function __construct($locales)
    {
        $this->locales = $locales;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                null,
                array(
                    'label' => 'content_block.form.title_label'
                )
            )
            ->add(
                'body',
                'ckeditor',
                array(
                    'label' => 'content_block.form.body_label',
                    'config_name' => 'default',
                    'attr' => array(
                        'rows' => 12
                    ),
                    'required' => false
                )
            )
            ->add(
                'image',
                'positibe_image_type',
                array(
                    'provider' => 'positibe_orm_media.image_provider',
                    'required' => false,
                    'label' => 'content_block.form.image_label'
                )
            )
            ->add(
                'locale',
                'choice',
                array(
                    'label' => 'content_block.form.locale_label',
                    'choices' => array_combine($this->locales, $this->locales)
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\OrmContentBundle\Entity\Blocks\ContentBlock',
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
        return 'positibe_content_block';
    }
} 
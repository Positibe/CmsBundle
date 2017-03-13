<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class ContentBlockType
 * @package Positibe\Bundle\CmsBundle\Form\Type
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
                null,
                array(
                    'label' => 'content_block.form.body_label',
//                    'config_name' => 'default',
                    'attr' => array(
                        'rows' => 12,
                        'class' => 'ckeditor'
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
                'data_class' => 'Positibe\Bundle\CmsBundle\Entity\Blocks\ContentBlock',
                'translation_domain' => 'PositibeCmsBundle'
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
        return 'positibe_cms_block';
    }
} 
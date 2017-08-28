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

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Positibe\Bundle\MediaBundle\Form\Type\ImageType;
use Positibe\Bundle\MediaBundle\Form\Type\MediaType;
use Positibe\Bundle\MediaBundle\Provider\MediaProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


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
                    'label' => 'content_block.form.title_label',
                )
            )
            ->add(
                'body',
                CKEditorType::class,
                array(
                    'label' => 'content_block.form.body_label',
                    'required' => false,
                    'config_name' => 'content',
                )
            )
            ->add(
                'image',
                ImageType::class,
                array(
                    'provider' => MediaProvider::IMAGE_PROVIDER,
                    'required' => false,
                    'label' => 'content_block.form.image_label',
                )
            )
            ->add(
                'locale',
                ChoiceType::class,
                array(
                    'label' => 'content_block.form.locale_label',
                    'choices' => array_combine($this->locales, $this->locales),
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\CmsBundle\Entity\ContentBlock',
            )
        );
    }

    public function getParent()
    {
        return AbstractBlockType::class;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'positibe_cms_block';
    }
} 
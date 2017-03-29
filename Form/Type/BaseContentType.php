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
use Positibe\Bundle\CmfRoutingExtraBundle\Form\Type\RoutePermalinkType;
use Positibe\Bundle\CmsBundle\Entity\BaseContent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class AbstractPageType
 * @package Positibe\Bundle\CmsBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class BaseContentType extends AbstractType
{
    private $locales;
    private $defaultLocale;

    public function __construct($locales, $defaultLocale)
    {
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var BaseContent $data */
        $data = $options['data'];
        $builder
            ->add(
                'title',
                null,
                array(
                    'label' => 'page.form.title_label',
                    'required' => true,
                )
            )
            ->add(
                'body',
                CKEditorType::class,
                array(
                    'label' => 'page.form.body_label',
                    'config_name' => 'content',
                )
            )
            ->add(
                'locale',
                ChoiceType::class,
                array(
                    'label' => 'page.form.locale_label',
                    'choices' => array_combine($this->locales, $this->locales),
                    'attr' => ['class' => 'change_locale'],
                )
            )
            ->add(
                'publishStartDate',
                DateTimeType::class,
                array(
                    'required' => false,
                    'label' => 'page.form.publish_start_label',
                    'widget' => 'single_text',
                    'attr' => array('class' => 'datetime-picker'),
                    'format' => 'dd/MM/yyyy HH:mm',
                )
            )
            ->add(
                'publishEndDate',
                DateTimeType::class,
                array(
                    'required' => false,
                    'label' => 'page.form.publish_end_label',
                    'widget' => 'single_text',
                    'attr' => array('class' => 'datetime-picker'),
                    'format' => 'dd/MM/yyyy HH:mm',
                )
            )
            ->add(
                'routes',
                RoutePermalinkType::class,
                array(
                    'label' => 'page.form.routes_label',
                    'content_has_routes' => $data,
                    'current_locale' => $data->getLocale(),
                )
            )
            ->add(
                'seoMetadata',
                SeoMetadataType::class,
                array(
                    'label' => 'page.form.seo_label',
                )
            );
        if ($options['data'] && $options['data']->getName()) {
            $builder->add('name', null, ['label' => 'page.form.name_label']);
        }

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\CmsBundle\Entity\BaseContent',
                'default_locale' => $this->defaultLocale,
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'positibe_base_content';
    }

} 
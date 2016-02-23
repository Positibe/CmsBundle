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

use Positibe\Bundle\OrmRoutingBundle\Builder\RouteBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class AbstractPageType
 * @package Positibe\Bundle\OrmContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class AbstractPageType extends AbstractType
{

    private $locales;
    private $defaultLocale;
    private $routeBuilder;

    private $resourceName;
    public function __construct(RouteBuilder $routeBuilder, $locales, $defaultLocale)
    {
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
        $this->routeBuilder = $routeBuilder;

        $resource = explode('Type', get_class($this));
        $this->resourceName = strtolower(preg_replace('/[A-Z]/', '_' . '\\0', lcfirst($resource[0])));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityArray = explode('\\', $options['data_class']);
        $entityName = array_pop($entityArray);
        $resourceName = strtolower(preg_replace('/[A-Z]/', '_' . '\\0', lcfirst($entityName)));
        $builder
            ->add(
                'title',
                null,
                array(
                    'label' => $resourceName . '.form.title_label',
                    'required' => true
                )
            )
            ->add(
                'body',
                null,
                array(
                    'label' => $resourceName . '.form.body_label',
                    'attr' => array(
                        'rows' => 12,
                        'class' => 'inbox-editor inbox-wysihtml5'
                    )
                )
            )
            ->add(
                'locale',
                'choice',
                array(
                    'label' => $resourceName . '.form.locale_label',
                    'choices' => array_combine($this->locales, $this->locales)
                )
            )
            ->add(
                'publishable',
                null,
                array(
                    'label' => $resourceName . '.form.publishable_label',
                    'required' => false
                )
            )
            ->add(
                'publishStartDate',
                'sonata_type_datetime_picker',
                array(
                    'dp_side_by_side' => true,
                    'dp_use_seconds' => false,
                    'required' => false,
                    'label' => $resourceName . '.form.publish_start_label',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'dp_language' => 'es',
                )
            )
            ->add(
                'publishEndDate',
                'sonata_type_datetime_picker',
                array(
                    'dp_side_by_side' => true,
                    'dp_use_seconds' => false,
                    'required' => false,
                    'label' => $resourceName . '.form.publish_end_label',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'dp_language' => 'es',
                )
            )
            ->add(
                'routes',
                'positibe_route_permalink',
                array(
                    'label' => $resourceName . '.form.routes_label',
                    'content_has_routes' => $options['data'],
                    'current_locale' => $options['data']->getLocale()
                )
            )
            ->add(
                'seoMetadata',
                new SeoMetadataType(),
                array(
                    'label' => $resourceName . '.form.seo_label',
                )
            )
            ->add(
                'image',
                'sonata_media_type',
                array(
                    'provider' => 'sonata.media.provider.image',
                    'context' => 'page',
                    'attr' => array(
                        'class' => 'fileupload-preview thumbnail',
                        'style' => 'display:none'
                    ),
                    'label' => $resourceName . '.form.image_label',
                    'required' => false
                )
            );
    }

    public function getResourceName()
    {
        return $this->resourceName;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\OrmContentBundle\Entity\AbstractPage',
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'positibe_abstract_page';
    }

} 
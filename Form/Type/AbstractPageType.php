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

use Positibe\Bundle\CmfRoutingExtraBundle\Factory\RouteFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class AbstractPageType
 * @package Positibe\Bundle\CmsBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class AbstractPageType extends AbstractType
{
    private $locales;
    private $defaultLocale;
    private $routeFactory;

    private $resourceName;

    public function __construct(RouteFactory $routeFactory, $locales, $defaultLocale)
    {
        $this->routeFactory = $routeFactory;
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
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
                'class' => 'ckeditor'
              ),
              'required' => false
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
            'datetime',
            array(
              'required' => false,
              'label' => $resourceName . '.form.publish_start_label',
              'widget' => 'single_text',
              'attr' => array('class' => 'datetime-picker'),
              'format' => 'dd/MM/yyyy HH:mm'
            )
          )
          ->add(
            'publishEndDate',
            'datetime',
            array(
              'label' => $resourceName . '.form.publish_end_label',
              'widget' => 'single_text',
              'attr' => array('class' => 'datetime-picker'),
              'format' => 'dd/MM/yyyy HH:mm',
              'required' => false,
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
            'positibe_image_type',
            array(
              'label' => $resourceName . '.form.image_label',
              'translation_domain' => 'messages',
              'required' => false
            )
          );
    }

    public function getResourceName()
    {
        if (!$this->resourceName) {
            $resource = explode('Type', get_class($this));
            $this->resourceName = strtolower(preg_replace('/[A-Z]/', '_' . '\\0', lcfirst($resource[0])));
        }

        return $this->resourceName;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          array(
            'data_class' => 'Positibe\Bundle\CmsBundle\Entity\AbstractPage',
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
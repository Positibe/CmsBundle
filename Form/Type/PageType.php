<?php

namespace Positibe\Bundle\ContentBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\ContentBundle\Entity\Repository\PageRepository;
use Positibe\Bundle\OrmRoutingBundle\Factory\RouteFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PageType
 * @package Positibe\Bundle\ContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PageType extends AbstractType
{
    private $locales;
    private $defaultLocale;
    private $routeBuilder;
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $entityManager, RouteFactory $routeBuilder, $locales, $defaultLocale)
    {
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
        $this->routeBuilder = $routeBuilder;
        $this->em = $entityManager;
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
              'label' => 'static_content.form.title_label',
              'required' => true
            )
          )
          ->add(
            'body',
            null,
            array(
              'label' => 'static_content.form.body_label',
              'attr' => array(
                'rows' => 12,
                'class' => 'ckeditor'
              )
            )
          )
          ->add(
            'locale',
            'choice',
            array(
              'label' => 'static_content.form.locale_label',
              'choices' => array_combine($this->locales, $this->locales)
            )
          )
          ->add(
            'customController',
            'choice',
            array(
              'label' => 'static_content.form.custom_controller_label',
              'choices' => array_combine(
                array_keys($this->routeBuilder->getController()),
                array_keys($this->routeBuilder->getController())
              ),
              'required' => false,
            )
          )
          ->add(
            'publishable',
            null,
            array(
              'label' => 'static_content.form.publishable_label',
              'required' => false
            )
          )
          ->add(
            'publishStartDate',
            'datetime',
            array(
              'required' => false,
              'label' => 'static_content.form.publish_start_label',
              'widget' => 'single_text',
              'attr' => array('class' => 'datetime-picker'),
              'format' => 'dd/MM/yyyy HH:mm'
            )
          )
          ->add(
            'publishEndDate',
            'datetime',
            array(
              'required' => false,
              'label' => 'static_content.form.publish_end_label',
              'widget' => 'single_text',
              'attr' => array('class' => 'datetime-picker'),
              'format' => 'dd/MM/yyyy HH:mm'
            )
          )
          ->add(
            'routes',
            'positibe_route_permalink',
            array(
              'label' => 'static_content.form.routes_label',
              'content_has_routes' => $options['data'],
              'current_locale' => $options['data']->getLocale()
            )
          )
//            ->add(
//                'menuNodes',
//                'collection',
//                array(
//                    'type' => new MenuNodeType($this->locales),
//                    'allow_add' => true,
//                    'allow_delete' => true,
//                    'options' => array(
//                        'required' => false,
//                        'attr' => array('class' => 'route')
//                    ),
//                    'required' => false,
//                    'label' => 'static_content.form.menus_label',
//                )
//            )
          ->add(
            'seoMetadata',
            new SeoMetadataType(),
            array(
              'label' => 'static_content.form.seo_label',
            )
          )
          ->add(
            'image',
            'positibe_image_type',
            array(
              'provider' => 'positibe_orm_media.image_provider',
              'required' => false
            )
          )
          ->add(
            'parent',
            'entity',
            array(
              'label' => 'static_content.form.parent_label',
              'class' => 'Positibe\Bundle\ContentBundle\Entity\Category',
              'choices' => $this->getCategoryTranslated($options)
            )
          )
          ->add(
            'featured',
            null,
            array(
              'label' => 'static_content.form.featured_label',
              'required' => false
            )
          );
    }

    /**
     * @param $options
     * @return mixed
     */
    private function getCategoryTranslated($options)
    {
        $locale = $options['data']->getLocale();
        $categories = $this->getCategoryRepository()->findAll();
        if ($locale !== $this->defaultLocale) {
            foreach ($categories as $category) {
                $category->setLocale($locale);
                $this->em->refresh($category);
            }
        }

        return $categories;
    }

    /**
     * @return PageRepository
     */
    private function getCategoryRepository()
    {
        return $this->em->getRepository('PositibeContentBundle:Category');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
          array(
            'data_class' => 'Positibe\Bundle\ContentBundle\Entity\Page',
            'translation_domain' => 'PositibeContentBundle'
          )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'positibe_page';
    }
}

<?php

namespace Positibe\Bundle\OrmContentBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\OrmContentBundle\Entity\Repository\PageRepository;
use Positibe\Bundle\OrmContentBundle\Model\ContentType;
use Positibe\Bundle\OrmRoutingBundle\Factory\RouteFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PageType
 * @package Positibe\Bundle\OrmContentBundle\Form\Type
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
                'ckeditor',
                array(
                    'label' => 'static_content.form.body_label',
                    'config_name' => 'default',
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
                'sonata_type_datetime_picker',
                array(
                    'dp_side_by_side' => true,
                    'dp_use_seconds' => false,
                    'required' => false,
                    'label' => 'static_content.form.publish_start_label',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'dp_language' => $this->defaultLocale,
                )
            )
            ->add(
                'publishEndDate',
                'sonata_type_datetime_picker',
                array(
                    'dp_side_by_side' => true,
                    'dp_use_seconds' => false,
                    'required' => false,
                    'label' => 'static_content.form.publish_end_label',
                    'format' => 'dd/MM/yyyy HH:mm',
                    'dp_language' => $this->defaultLocale,
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
                    'class' => 'Positibe\Bundle\OrmContentBundle\Entity\Category',
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
            )
//            ->add(
//                'contentType',
//                'hidden',
//                array(
//                    'label' => 'static_content.form.content_type_label'
//                )
//            )
        ;
    }

    /**
     * @param $options
     * @return mixed
     */
    private function getCategoryTranslated($options)
    {
        $locale = $options['data']->getLocale();
        $categories = $this->getCategoryRepository()->findByContentType(
            ContentType::TYPE_CATEGORY,
            $locale
        );
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
        return $this->em->getRepository('PositibeOrmContentBundle:Category');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\OrmContentBundle\Entity\Page',
                'translation_domain' => 'PositibeOrmContentBundle'
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

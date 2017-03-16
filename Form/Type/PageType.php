<?php

namespace Positibe\Bundle\CmsBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Positibe\Bundle\CmfRoutingExtraBundle\Form\Type\RoutePermalinkType;
use Positibe\Bundle\CmsBundle\Repository\PageRepository;
use Positibe\Bundle\CmfRoutingExtraBundle\Factory\RouteFactory;
use Positibe\Bundle\MediaBundle\Form\Type\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PageType
 * @package Positibe\Bundle\CmsBundle\Form\Type
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
                    'required' => true,
                )
            )
            ->add(
                'body',
                CKEditorType::class,
                array(
                    'label' => 'static_content.form.body_label',
                    'config_name' => 'content'
                )
            )
            ->add(
                'locale',
                ChoiceType::class,
                array(
                    'label' => 'static_content.form.locale_label',
                    'choices' => array_combine($this->locales, $this->locales),
                    'attr' => ['class' => 'change_locale form-control-sm']
                )
            )
            ->add(
                'customController',
                ChoiceType::class,
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
                CheckboxType::class,
                array(
                    'label' => 'static_content.form.publishable_label',
                    'required' => false,
                )
            )
            ->add(
                'publishStartDate',
                DateTimeType::class,
                array(
                    'required' => false,
                    'label' => 'static_content.form.publish_start_label',
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
                    'label' => 'static_content.form.publish_end_label',
                    'widget' => 'single_text',
                    'attr' => array('class' => 'datetime-picker'),
                    'format' => 'dd/MM/yyyy HH:mm',
                )
            )
            ->add(
                'routes',
                RoutePermalinkType::class,
                array(
                    'label' => 'static_content.form.routes_label',
                    'content_has_routes' => $options['data'],
                    'current_locale' => $options['data']->getLocale(),
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
                SeoMetadataType::class,
                array(
                    'label' => 'static_content.form.seo_label',
                )
            )
            ->add(
                'image',
                ImageType::class,
                array(
                    'provider' => 'positibe_orm_media.image_provider',
                    'required' => false,
                )
            )
            ->add(
                'parent',
                EntityType::class,
                array(
                    'label' => 'static_content.form.parent_label',
                    'class' => 'Positibe\Bundle\CmsBundle\Entity\Category',
                    'choices' => $this->getCategoryTranslated($options),
                )
            )
            ->add(
                'featured',
                null,
                array(
                    'label' => 'static_content.form.featured_label',
                    'required' => false,
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
        return $this->em->getRepository('PositibeCmsBundle:Category');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\CmsBundle\Entity\Page',
                'translation_domain' => 'PositibeCmsBundle',
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

<?php

namespace Positibe\Bundle\CmsBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\CmsBundle\Entity\Category;
use Positibe\Bundle\CmsBundle\Entity\Page;
use Positibe\Bundle\MediaBundle\Form\Type\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
    private $entityManager;
    private $availableControllers;
    private $availableTemplates;

    public function __construct(EntityManager $entityManager, $availableControllers, $availableTemplates)
    {
        $this->entityManager = $entityManager;
        $this->availableControllers = $availableControllers;
        $this->availableTemplates = $availableTemplates;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $this->getCategoryTranslated($options['data'], $options['default_locale']);
        if ($this->availableControllers) {
            $builder->add(
                'customController',
                ChoiceType::class,
                array(
                    'label' => 'page.form.custom_controller_label',
                    'choices' => array_combine(
                        $this->availableControllers,
                        $this->availableControllers
                    ),
                    'required' => false,
                )
            );
        }
        if ($this->availableTemplates) {
            $builder->add(
                'customTemplate',
                ChoiceType::class,
                array(
                    'label' => 'page.form.custom_template_label',
                    'choices' => array_combine(
                        $this->availableTemplates,
                        $this->availableTemplates
                    ),
                    'required' => false,
                )
            );
        }
        $builder
            ->add(
                'image',
                ImageType::class,
                array(
                    'provider' => 'positibe_orm_media.image_provider',
                    'required' => false,
                )
            )
            ->add(
                'category',
                EntityType::class,
                array(
                    'label' => 'page.form.parent_label',
                    'class' => 'Positibe\Bundle\CmsBundle\Entity\Category',
                    'choices' => $categories,
                    'required' => false,
                )
            )
            ->add(
                'featured',
                null,
                array(
                    'label' => 'page.form.featured_label',
                    'required' => false,
                )
            );
    }

    /**
     * @param Page $content
     * @param $defaultLocale
     * @return \Positibe\Bundle\CmsBundle\Entity\Category[]
     */
    private function getCategoryTranslated(Page $content, $defaultLocale)
    {
        $locale = $content->getLocale();
        /** @var Category[] $categories */
        $categories = $this->entityManager->getRepository('PositibeCmsBundle:Category')->findAll();
        if ($locale !== $defaultLocale) {
            foreach ($categories as $category) {
                $category->setLocale($locale);
                $this->entityManager->refresh($category);
            }
        }

        return $categories;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\CmsBundle\Entity\Page'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'positibe_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseContentType::class;
    }
}

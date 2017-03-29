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

use Positibe\Bundle\CmsBundle\Form\EventListener\ContentFieldListener;
use Positibe\Bundle\MenuBundle\Menu\Factory\ContentAwareFactory;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MenuNodeType
 * @package Positibe\Bundle\CmsBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeType extends AbstractType
{
    private $locales;
    private $publicRoutes;
    private $contentClass;
    private $menuNodeClass;

    public function __construct($menuNodeClass, $locales)
    {
        $this->menuNodeClass = $menuNodeClass;
        $this->locales = $locales;
    }

    /**
     * @param mixed $contentClass
     */
    public function setContentClass($contentClass)
    {
        $this->contentClass = $contentClass;
    }

    /**
     * @param mixed $locales
     */
    public function setLocales($locales)
    {
        $this->locales = $locales;
    }

    /**
     * @param mixed $publicRoutes
     */
    public function setPublicRoutes($publicRoutes)
    {
        $this->publicRoutes = $publicRoutes;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new ContentFieldListener());

        $builder
            ->add(
                'label',
                null,
                array(
                    'label' => 'menu_node.form.label_label',
                    'required' => false,
                )
            )
            ->add(
                'locale',
                ChoiceType::class,
                array(
                    'choices' => array_combine($this->locales, $this->locales),
                    'label' => 'menu_node.form.locale_label',
                )
            )
            ->add(
                'display',
                null,
                array(
                    'label' => 'menu_node.form.display_label',
                    'required' => false,
                )
            )
            ->add(
                'displayChildren',
                null,
                array('label' => 'menu_node.form.display_children_label', 'required' => false)
            )
            ->add(
                'linkAttributes',
                ImmutableArrayType::class,
                array(
                    'keys' => array(
                        array('class', 'text', array('required' => false, 'label' => 'class=""')),
                        array('target', 'text', array('required' => false, 'label' => 'target=""')),
                    ),
                    'label' => 'menu_node.form.link_attributes_label',
                )
            )
            ->add(
                'labelAttributes',
                ImmutableArrayType::class,
                array(
                    'keys' => array(
                        array('class', 'text', array('required' => false, 'label' => 'class=""')),
                    ),
                    'label' => 'menu_node.form.label_attributes_label',
                )
            )
            ->add(
                'linkType',
                ChoiceType::class,
                array(
                    'choices' => array(
                        'menu_node.form.link_type_choices.uri' => ContentAwareFactory::LINK_TYPE_URI,
                        'menu_node.form.link_type_choices.route' => ContentAwareFactory::LINK_TYPE_ROUTE,
                        'menu_node.form.link_type_choices.content' => ContentAwareFactory::LINK_TYPE_CONTENT,
                    ),
                    'label' => 'menu_node.form.link_type_label',
                )
            )
            ->add(
                'uri',
                null,
                array(
                    'label' => 'menu_node.form.uri_label',
                    'required' => false,
                )
            )
            ->add(
                'route',
                ChoiceType::class,
                array(
                    'label' => 'menu_node.form.route_label',
                    'choices' => array_combine($this->publicRoutes, $this->publicRoutes),
                    'required' => false,
                )
            )
            ->add(
                'contentClass',
                ChoiceType::class,
                array(
                    'choices' => array_combine($this->contentClass, $this->contentClass),
                    'label' => 'menu_node.form.content_class_label',
                    'required' => false,
                )
            )
            ->add(
                'iconClass',
                null,
                array(
                    'label' => 'menu_node.form.icon_class_class_label',
                    'required' => false,
                )
            )
            ->add(
                'childrenAttributes',
                ImmutableArrayType::class,
                array(
                    'keys' => array(
                        array('id', 'text', array('required' => false, 'label' => 'id=""')),
                        array('class', 'text', array('required' => false, 'label' => 'class=""')),
                    ),
                    'label' => 'menu_node.form.children_attributes_label',
                )
            )
//            ->add('routeParameters')
//            ->add('extras')
//            ->add('routeAbsolute')
//            ->add(
//                'attributes',
//                'sonata_type_immutable_array',
//                array(
//                    'label' => 'menu_node.form.attributes_label',
//                    'keys' => array(
//                        array('id',      'text', array('required' => false)),
//                        array('class',   'text',  array('required' => false)),
//                    )
//                )
//            )
//            ->add(
//                'childrenAttributes',
//                'sonata_type_immutable_array',
//                array(
//                    'keys' => array(
//                        array('id', 'text', array('required' => false)),
//                        array('class', 'text', array('required' => false)),
//                    ),
//                    'label' => 'menu_node.form.children_attributes_label',
//                )
//            )
        ;


    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->menuNodeClass,
            )
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'positibe_menu_node';
    }
}

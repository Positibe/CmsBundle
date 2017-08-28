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

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\CmsBundle\Entity\Category;
use Positibe\Bundle\CmsBundle\Entity\MenuNode;
use Positibe\Bundle\CmsBundle\Entity\Page;
use Positibe\Bundle\CmsBundle\Form\DataTransformer\ContentIdDataTransformer;
use Positibe\Bundle\MenuBundle\Menu\Factory\ContentAwareFactory;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
    protected $em;

    public function __construct(EntityManager $entityManager, $menuNodeClass, $locales)
    {
        $this->em = $entityManager;
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
        $builder
            ->add(
                'name',
                null,
                array(
                    'label' => 'menu_node.form.name_label',
                    'required' => true,
                )
            )
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
                    'attr' => ['class' => 'menu_link_type'],
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
            ->add('position', null, ['label' => 'menu_node.form.position_label', 'required' => false])
            ->add(
                'contentId',
                null,
                array(
                    'required' => false,
                    'label' => 'menu_node.form.content_label',
                )
            );

        $builder->get('contentId')->addModelTransformer(new ContentIdDataTransformer());

        $em = $this->em;
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($em) {
                /** @var MenuNode $menu */
                $menu = $event->getData();
                if ($menu->getLinkType() === ContentAwareFactory::LINK_TYPE_CONTENT) {
                    $class = $event->getForm()->get('contentClass')->getData();
                    $id = $event->getForm()->get('contentId')->getData();
                    $menu->setContentId($class.':'.$id);
                    if ($class === Page::class || $class === Category::class) {
                        $menu->setPage($em->getRepository($class)->find($id));
                    } else {
                        $menu->setPage(null);
                    }
                }


            }
        );
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

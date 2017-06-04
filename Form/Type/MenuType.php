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

use Positibe\Bundle\MenuBundle\Menu\Factory\ContentAwareFactory;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MenuType
 * @package Positibe\Bundle\CmsBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuType extends AbstractType
{
    private $menuNodeClass;

    public function __construct($menuNodeClass)
    {
        $this->menuNodeClass = $menuNodeClass;
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
                )
            )
            ->add(
                'display',
                null,
                array(
                    'label' => 'menu_node.form.display_label',
                    'required' => false
                )
            )
            ->add(
                'displayChildren',
                null,
                array('label' => 'menu_node.form.display_children_label', 'required' => false)
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
                    'required' => false
                )
            )
            ->add(
                'linkType',
                HiddenType::class,
                array(
                    'data' => ContentAwareFactory::LINK_TYPE_ROOT
                )
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->menuNodeClass
            )
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'positibe_menu';
    }
}

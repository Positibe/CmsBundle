<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class RouteType
 * @package Positibe\Bundle\ContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class RouteType extends AbstractType {

    private $locales;
    private $controllers;

    public function __construct($locales, array $controllers = array())
    {
        $this->locales = $locales;
        $this->controllers = $controllers;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('static_prefix', null, array(
                    'label' => 'route.form.static_prefix_label',
                ))
//            ->add('locale', 'choice', array(
//                    'label' => 'route.form.locale_label',
//                    'choices' => array_combine($this->locales, $this->locales)
//                ))
//            ->add('controller', 'choice', array(
//                    'label' => 'route.form.controller_label',
//                    'choices' => array_combine($this->controllers, $this->controllers),
//                    'required' => false
//                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Positibe\Bundle\OrmRoutingBundle\Entity\Route',
                'translation_domain' => 'PositibeContentBundle'
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'positibe_content_route';
    }
} 
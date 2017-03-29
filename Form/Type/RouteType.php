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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class RouteType
 * @package Positibe\Bundle\CmsBundle\Form\Type
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
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Symfony\Cmf\Bundle\CmfRoutingBundle\Doctrine\Orm\Route',
            ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'positibe_cms_route';
    }
} 
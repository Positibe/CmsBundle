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
 * Class WebsiteFormType
 * @package AppBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class WebsiteFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'domain',
                null,
                [
                    'label' => 'website.app_website.domain_label',
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'website.app_website.description_label',
                ]
            )
            ->add(
                'template',
                null,
                [
                    'label' => 'website.app_website.template_label',
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'website.app_website.enabled_label',
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Website']);
    }
}

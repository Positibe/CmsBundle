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

use Positibe\Bundle\CmsBundle\Entity\Block;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractBlockType
 * @package Positibe\Bundle\CmsBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class AbstractBlockType extends AbstractType
{
    protected $templatePositions;
    protected $publicRoutes;
    protected $roles;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                null,
                array(
                    'label' => 'abstract_block.form.name_label'
                )
            )
            ->add(
                'templatePosition',
                ChoiceType::class,
                array(
                    'label' => 'abstract_block.form.block_location_label',
                    'choices' => array_combine($this->templatePositions, $this->templatePositions),
                    'required' => false,
                )
            )
            ->add(
                'publishable',
                null,
                array(
                    'label' => 'abstract_block.form.publishable_label',
                    'required' => false,
                )
            )
            ->add(
                'publishStartDate',
                null,
                array(
                    'required' => false,
                    'label' => 'abstract_block.form.publish_start_label',
                    'widget' => 'single_text',
                    'attr' => array('class' => 'datetime-picker'),
                    'format' => 'dd/MM/yyyy HH:mm',
                )
            )
            ->add(
                'publishEndDate',
                null,
                array(

                    'label' => 'abstract_block.form.publish_end_label',
                    'widget' => 'single_text',
                    'attr' => array('class' => 'datetime-picker'),
                    'format' => 'dd/MM/yyyy HH:mm',
                    'required' => false,
                )
            )
            ->add(
                'always',
                null,
                array(
                    'label' => 'visibility_block.form.always_label',
                    'required' => false,
                )
            )
            ->add(
                'categories',
                null,
                array(
                    'label' => 'visibility_block.form.categories_label',
                    'multiple' => true,
                    'expanded' => true,
                )
            )
            ->add(
                'pages',
                null,
                array(
                    'required' => false,
                    'label' => 'visibility_block.form.pages_label',
                    'class' => 'Positibe\Bundle\CmsBundle\Entity\Page',
                    'multiple' => true,
                    'attr' => array('class' => 'chosen-select form-control'),
                )
            )
            ->add(
                'routes',
                ChoiceType::class,
                array(
                    'label' => 'visibility_block.form.routes_label',
                    'choices' => array_combine($this->publicRoutes, $this->publicRoutes),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                )
            );
        if ($this->roles) {
            $builder->add(
                'roles',
                ChoiceType::class,
                array(
                    'label' => 'visibility_block.form.roles_label',
                    'choices' => array_combine($this->roles, $this->roles),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                )
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Block::class,
            )
        );
    }

    /**
     * @param mixed $publicRoutes
     */
    public function setPublicRoutes($publicRoutes)
    {
        $this->publicRoutes = $publicRoutes;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param mixed $templatePositions
     */
    public function setTemplatePositions($templatePositions)
    {
        $this->templatePositions = $templatePositions;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'positibe_abstract_block';
    }

} 
<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class AbstractVisibilityBlockType
 * @package Positibe\Bundle\OrmContentBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class AbstractVisibilityBlockType extends AbstractType
{
    protected $publicRoutes;
    protected $roles;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'always',
                null,
                array(
                    'label' => 'visibility_block.form.always_label'
                )
            )
            ->add(
                'categories',
                null,
                array(
                    'label' => 'visibility_block.form.categories_label',
                    'multiple' => true,
                    'expanded' => true
                )
            )
            ->add(
                'pages',
                'genemu_jquerychosen_entity',
                array(
                    'required' => false,
                    'label' => 'visibility_block.form.pages_label',
                    'class' => 'Positibe\Bundle\OrmContentBundle\Entity\Page',
                    'multiple' => true,
                    'attr' => array('class' => 'chosen-select form-control'),
                    'placeholder' => 'visibility_block.form.pages_label',
                    'empty_value' => 'visibility_block.form.pages_label'
                )
            )
            ->add(
                'routes',
                'choice',
                array(
                    'label' => 'visibility_block.form.routes_label',
                    'choices' => array_combine($this->publicRoutes, $this->publicRoutes),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false
                )
            )
            ->add(
                'roles',
                'choice',
                array(
                    'label' => 'visibility_block.form.roles_label',
                    'choices' => array_combine($this->roles, $this->roles),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                )
            );
    }

    /**
     * @return mixed
     */
    public function getPublicRoutes()
    {
        return $this->publicRoutes;
    }

    /**
     * @param mixed $publicRoutes
     */
    public function setPublicRoutes($publicRoutes)
    {
        $this->publicRoutes = $publicRoutes;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getParent()
    {
        return 'positibe_abstract_block';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\OrmContentBundle\Entity\Abstracts\AbstractVisibilityBlock',
                'translation_domain' => 'PositibeOrmContentBundle'
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'positibe_block_visibility';
    }

} 
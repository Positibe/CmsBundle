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

use Doctrine\ORM\EntityRepository;
use Positibe\Bundle\CmsBundle\Entity\MenuNode;
use Positibe\Bundle\MenuBundle\Menu\Factory\ContentAwareFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class MenuBlockType
 * @package Positibe\Bundle\CoreBundle\Form\Type
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'menu',
            EntityType::class,
            array(
                'class' => MenuNode::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')->where('o.linkType = :rootType')->setParameter(
                        'rootType',
                        ContentAwareFactory::LINK_TYPE_ROOT
                    );
                },
                'attr' => array(
                    'class' => 'chosen-select form-control'
                )
            )
        );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Positibe\Bundle\CmsBundle\Entity\MenuBlock'
            )
        );
    }

    public function getParent()
    {
        return AbstractBlockType::class;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'positibe_menu_block';
    }

} 
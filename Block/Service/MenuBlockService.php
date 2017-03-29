<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\Block\Service;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MenuBlockService
 * @package Positibe\Bundle\CoreBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuBlockService extends CmsBlockService
{
    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $block = $blockContext->getBlock();

        // if the referenced target menu does not exist, we just skip the rendering
        if (!$block->getEnabled() || null === $blockContext->getSetting('menu_name')) {
            return $response ?: new Response();
        }

        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'menu' => $this->getMenu($blockContext),
                'menu_options' => $this->getMenuOptions($blockContext->getSettings()),
                'block' => $blockContext->getBlock(),
            ),
            $response
        );
    }

    protected function getMenu(BlockContextInterface $blockContext)
    {
        if (method_exists($blockContext->getBlock(), 'getMenu') && method_exists($blockContext->getBlock()->getMenu(), 'getName') ) {
            $menuName = $blockContext->getBlock()->getMenu()->getName();
        } else {
            $menuName = $blockContext->getSetting('menu_name');
        }

        return $menuName;
    }

    /**
     * Replaces setting keys with knp menu item options keys
     *
     * @param array $settings
     *
     * @return array
     */
    protected function getMenuOptions(array $settings)
    {
        $mapping = array(
            'current_class' => 'currentClass',
            'first_class' => 'firstClass',
            'last_class' => 'lastClass',
            'safe_labels' => 'allow_safe_labels',
            'menu_template' => 'template',
        );

        $options = array();

        foreach ($settings as $key => $value) {
            if (array_key_exists($key, $mapping) && null !== $value) {
                $options[$mapping[$key]] = $value;
            }
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'template' => 'SonataBlockBundle:Block:block_core_menu.html.twig',
                'request' => false,
                'menu_name' => null,
                'current_class'  => 'active',
                'first_class'    => false,
                'last_class'     => false,
                'current_uri'    => null,
                'menu_class'     => "list-group",
                'children_class' => "list-group-item",
                'menu_template'  => null,
            )
        );
    }
} 
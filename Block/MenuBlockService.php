<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmContentBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\MenuBlockService as SonataMenuBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MenuBlockService
 * @package Positibe\Bundle\CmfBundle\Block
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuBlockService extends SonataMenuBlockService
{
    /** @var  ContainerInterface */
    private $container;

    public function getCacheKeys(BlockInterface $block)
    {
        $cacheKeys = parent::getCacheKeys($block);

        $cacheKeys['block_id'] = $block->getName();
        $cacheKeys['request_uri'] = $this->container->get('request_stack')->getMasterRequest()->getRequestUri();

        return $cacheKeys;
    }

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
            $settings = $blockContext->getSettings();
            $menuName = $settings['menu_name'];
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

        $options['template'] = 'menu/main.html.twig';

        foreach ($settings as $key => $value) {
            if (array_key_exists($key, $mapping) && null !== $value) {
                $options[$mapping[$key]] = $value;
            }
        }

        return $options;
    }

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param mixed $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }
} 
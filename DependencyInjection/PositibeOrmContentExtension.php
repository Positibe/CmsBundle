<?php

namespace Positibe\Bundle\OrmContentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PositibeOrmContentExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('positibe.menu_node.class', 'Positibe\Bundle\OrmContentBundle\Entity\MenuNode');

        $container->getDefinition('positibe_orm_media.form.visibility_block_type')
            ->addMethodCall('setRoles', array($config['roles']))
            ->addMethodCall('setPublicRoutes', array($config['public_routes']));
    }
}

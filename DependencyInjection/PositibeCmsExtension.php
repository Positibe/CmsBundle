<?php

namespace Positibe\Bundle\CmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PositibeCmsExtension extends Extension
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
        $loader->load('block_services.yml');
        $loader->load('cmf_seo_extractor_services.yml');

        $container->getDefinition('positibe_menu.menu_node_form_type')
            ->addMethodCall('setContentClass', array($config['content_class']))
            ->addMethodCall('setPublicRoutes', array($config['public_routes']));


        $container->setParameter('positibe.menu_node.class', 'Positibe\Bundle\CmsBundle\Entity\MenuNode');

        $container->getDefinition('positibe_cms.form.block_type')
            ->addMethodCall('setRoles', array($config['roles']))
            ->addMethodCall('setPublicRoutes', array($config['public_routes']))
            ->addMethodCall('setTemplatePositions', array($config['template_positions']));

        $container->getDefinition('positibe_cms.website_entity_listener')->addMethodCall(
            'setEntities',
            [$config['entities_with_host']]
        );

        $this->addClassesToCompile(
            array(
                'Symfony\\Cmf\\Bundle\\CoreBundle\\EventListener\\PublishWorkflowListener',
                'Symfony\\Cmf\\Bundle\\SeoBundle\\EventListener\\ContentListener',
                'Lunetics\\LocaleBundle\\EventListener\\LocaleListener',
                'Stof\\DoctrineExtensionsBundle\\EventListener\\LocaleListener',
                'Gedmo\\Translatable\\TranslatableListener',
            )
        );


    }
}

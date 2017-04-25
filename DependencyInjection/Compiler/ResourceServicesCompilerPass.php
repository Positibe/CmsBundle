<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\CmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class ResourceServicesCompilerPass
 * @package Positibe\Bundle\CmsBundle\DependencyInjection\Compiler
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ResourceServicesCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $repositories = [
            'positibe.repository.menu',
            'positibe.repository.block',
            'positibe.repository.content_block',
            'positibe.repository.gallery_block',
            'positibe.repository.menu_block',
            'positibe.repository.page_block',
            'positibe.repository.block',
            'positibe.repository.page',
        ];

        foreach ($repositories as $repository) {
            $container->getDefinition($repository)
                ->addMethodCall(
                    'setRequestStack',
                    [new Reference('request_stack')]
                );
        }

        //@fixme Remove when Sonata Cache support cache apcu
        $container->getDefinition('sonata.cache.apc')->setClass('Positibe\Bundle\CmsBundle\Cache\Sonata\ApcCache');
    }

} 
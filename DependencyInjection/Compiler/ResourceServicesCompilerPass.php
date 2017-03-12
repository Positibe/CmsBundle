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
        $container->getDefinition('positibe.repository.menu')
          ->addMethodCall(
            'setRequestStack',
            [new Reference('request_stack')]
          );
        $container->getDefinition('positibe.repository.block')
          ->addMethodCall(
            'setRequestStack',
            [new Reference('request_stack')]
          );

        $container->getDefinition('positibe.repository.content_block')
          ->addMethodCall(
            'setRequestStack',
            [new Reference('request_stack')]
          );

        $container->getDefinition('positibe.repository.gallery_block')
          ->addMethodCall(
            'setRequestStack',
            [new Reference('request_stack')]
          );

        $container->getDefinition('positibe.repository.menu_block')
          ->addMethodCall(
            'setRequestStack',
            [new Reference('request_stack')]
          );
        $container->getDefinition('positibe.repository.page_block')
          ->addMethodCall(
            'setRequestStack',
            [new Reference('request_stack')]
          );

        $container->getDefinition('positibe.repository.block')
          ->addMethodCall(
            'setRequestStack',
            [new Reference('request_stack')]
          );

        $container->getDefinition('positibe.repository.page')
          ->addMethodCall(
            'setRequestStack',
            [new Reference('request_stack')]
          );
    }

} 
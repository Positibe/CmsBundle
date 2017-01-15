<?php

namespace Positibe\Bundle\ContentBundle;

use Positibe\Bundle\ContentBundle\DependencyInjection\Compiler\ContentCompilerPass;
use Positibe\Bundle\ContentBundle\DependencyInjection\Compiler\ResourceServicesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeContentBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ContentCompilerPass());
        $container->addCompilerPass(new ResourceServicesCompilerPass());
    }
}

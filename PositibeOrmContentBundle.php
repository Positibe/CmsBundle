<?php

namespace Positibe\Bundle\OrmContentBundle;

use Positibe\Bundle\OrmContentBundle\DependencyInjection\Compiler\OrmContentCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PositibeOrmContentBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new OrmContentCompilerPass());
    }
}

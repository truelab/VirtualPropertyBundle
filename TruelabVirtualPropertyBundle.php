<?php

namespace Truelab\VirtualPropertyBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Truelab\VirtualPropertyBundle\DependencyInjection\CompilerPass\VirtualPropertyCompilerPass;

class TruelabVirtualPropertyBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new VirtualPropertyCompilerPass());
    }
}

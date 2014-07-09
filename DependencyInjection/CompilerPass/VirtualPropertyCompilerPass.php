<?php

namespace Truelab\VirtualPropertyBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class VirtualPropertyCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('truelab_virtual_property.virtual_property_generator_bag')) {
            return;
        }

        $definition = $container->getDefinition(
            'truelab_virtual_property.virtual_property_generator_bag'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'truelab_virtual_property.virtual_property_generator'
        );
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addGenerator',
                    array(new Reference($id), $attributes["class"], $attributes["property"], $attributes["method"])
                );
            }
        }
    }
}
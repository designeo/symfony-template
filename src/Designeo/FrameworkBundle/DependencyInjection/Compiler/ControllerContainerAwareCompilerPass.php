<?php

namespace Designeo\FrameworkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 *
 * @author Tomas Polivka <draczris@gmail.com>
 * @copyright 2013 Draczris
 */
class ControllerContainerAwareCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getDefinitions() as $definition) {
            if (preg_match('/Controller$/', $definition->getClass()) && !$definition->hasMethodCall('setContainer')) {
                $definition->addMethodCall(
                    'setContainer',
                    array(new Reference('service_container'))
                );
            }
        }
    }
}
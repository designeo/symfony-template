<?php

namespace DesigneoBundle;

use DesigneoBundle\DependencyInjection\Compiler\ControllerContainerAwareCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class DesigneoBundle
 * @package DesigneoBundle
 */
class DesigneoBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build( ContainerBuilder $container )
    {
        parent::build($container);

        // add setContainer call for all controllers
        $container->addCompilerPass(new ControllerContainerAwareCompilerPass());
    }
}

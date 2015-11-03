<?php

namespace Designeo\FrameworkBundle;

use Designeo\FrameworkBundle\DependencyInjection\Compiler\ControllerContainerAwareCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class FrameworkBundle
 * @package FrameworkBundle
 */
class DesigneoFrameworkBundle extends Bundle
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

<?php

namespace AppBundle;

use AppBundle\Security\Factory\ApiLoginFirewallFactory;
use AppBundle\Security\Factory\RestFirewallFactory;
use Designeo\FrameworkBundle\DependencyInjection\Compiler\ControllerContainerAwareCompilerPass;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AppBundle
 * @package AppBundle
 */
class AppBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build( ContainerBuilder $container )
    {
        parent::build($container);

        // add setContainer call for all controllers
        $container->addCompilerPass(new ControllerContainerAwareCompilerPass());

        /** @var SecurityExtension $extension */
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new RestFirewallFactory());
        $extension->addSecurityListenerFactory(new ApiLoginFirewallFactory());
    }
}

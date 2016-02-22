<?php

namespace AppBundle\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class ApiLoginFirewallFactory implements SecurityFactoryInterface
{

    /**
     * @param ContainerBuilder  $container
     * @param $id
     * @param $config
     * @param $userProvider
     * @param $defaultEntryPoint
     * @return array
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {

        $providerId = 'app.security.provider.api_v1_login.'.$id;

        $container->setDefinition($providerId, new DefinitionDecorator('app.security.provider.api_v1_login'));

        $listenerId = 'app.security.listener.api_v1_login.'.$id;
        $container->setDefinition($listenerId, new DefinitionDecorator('app.security.listener.api_v1_login'))
            ->replaceArgument(2, $config['debugAddresses']);

        $data = array($providerId, $listenerId, $defaultEntryPoint);

        return $data;
    }

    /**
     * Defines the position at which the provider is called.
     * Possible values: pre_auth, form, http, and remember_me.
     *
     * @return string
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return 'api_login';
    }

    /**
     * @param NodeDefinition $builder
     */
    public function addConfiguration(NodeDefinition $builder)
    {

        $builder
            ->children()
            ->arrayNode('secret')
            ->prototype('scalar')
            ->end()
            ->end()
            ->arrayNode('debugAddresses')
            ->prototype('scalar')
            ->end()
            ->end()
        ;
    }
}
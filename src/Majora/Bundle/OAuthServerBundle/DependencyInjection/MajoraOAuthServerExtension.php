<?php

namespace Majora\Bundle\OAuthServerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MajoraOAuthServerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'majora_oauth_server';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // override server default server definition
        if (!$container->hasDefinition('majora.oauth.server')) {
            return;
        }

        $serverDefinition = $container->getDefinition('majora.oauth.server');
        $serverDefinition->replaceArgument(1, new Reference($config['application']['loader']));
        $serverDefinition->replaceArgument(2, $config['access_token']['ttl']);
        $serverDefinition->replaceArgument(3, $config['access_token']['class']);

        // password extension
        if ($container->hasDefinition('majora.oauth.grant_extension.password')) {
            $passwordExtensionDefinition = $container->getDefinition('majora.oauth.grant_extension.password');
            $passwordExtensionDefinition->replaceArgument(0, new Reference($config['account']['loader']));
        }

        // refresh_token extension
        if ($container->hasDefinition('majora.oauth.grant_extension.refresh_token')) {
            $passwordExtensionDefinition = $container->getDefinition('majora.oauth.grant_extension.refresh_token');
            $passwordExtensionDefinition->replaceArgument(0, new Reference($config['account']['loader']));
            $passwordExtensionDefinition->replaceArgument(1, new Reference($config['refresh_token']['loader']));
        }

        // token generator
        if ($container->hasDefinition('majora.oauth.random_generator')) {
            $randomGeneratorDefinition = $container->getDefinition('majora.oauth.random_generator');
            $randomGeneratorDefinition->replaceArgument(0, $config['secret']);
        }

        // Driver type
        if ($config['db_driver']) {
            $container->setParameter($this->getAlias().'.driver.'.$config['db_driver'], true);
        }
    }
}

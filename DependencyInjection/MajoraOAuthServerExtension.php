<?php

namespace Majora\OAuthServerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class MajoraOAuthServerExtension.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class MajoraOAuthServerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('majora_oauth_server.storage.access_token', $config['storage']['access_token']);
        $container->setParameter('majora_oauth_server.storage.auth_code', $config['storage']['auth_code']);
        $container->setParameter('majora_oauth_server.storage.client', $config['storage']['client']);
        $container->setParameter('majora_oauth_server.storage.refresh_token', $config['storage']['refresh_token']);
        $container->setParameter('majora_oauth_server.storage.user', $config['storage']['user']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'majora_oauth_server';
    }
}

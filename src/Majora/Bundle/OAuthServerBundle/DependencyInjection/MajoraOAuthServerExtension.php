<?php

namespace Majora\Bundle\OAuthServerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

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

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));
        $loader->load('server.xml');
        $loader->load('extensions.xml');
        $loader->load('empty.xml');
        $loader->load('orm.xml');

        if (!$container->hasDefinition('majora.oauth.server')) {
            return;
        }

        // token generator
        $randomGeneratorDefinition = $container->getDefinition('majora.oauth.random_generator');
        $randomGeneratorDefinition->replaceArgument(0, $config['secret']);

        // server
        $serverDefinition = $container->getDefinition('majora.oauth.server');
        $serverDefinition->replaceArgument(3, array(
            'access_token_class' => $config['access_token']['class'],
            'access_token_ttl' => $config['access_token']['ttl'],
            'refresh_token_class' => $config['refresh_token']['class'],
            'refresh_token_ttl' => $config['refresh_token']['ttl'],
        ));

        // aliases generation
        foreach (array('access_token', 'refresh_token', 'application', 'account') as $entity) {
            foreach (array('loader', 'repository') as $serviceAlias) {
                foreach ($config[$entity][$serviceAlias] as $driver => $parameters) {

                    // given service id or build one from registered strategies
                    $serviceId = $driver == 'id' ?
                        $parameters :
                        sprintf('majora.oauth.%s.%s_%s', $entity, $driver, $serviceAlias)
                    ;

                    // publish given service
                    $container->setAlias(
                        sprintf('majora.oauth.%s.%s', $entity, $serviceAlias),
                        $serviceId
                    );

                    // register parameters under driver if given
                    //
                    // !! implements here a better strategy !!
                    //
                    if (is_array($parameters)) {
                        foreach ($parameters as $key => $value) {
                            $container->setParameter(
                                sprintf('majora.oauth.%s.%s_%s.%s', $entity, $driver, $serviceAlias, $key),
                                $value
                            );
                        }
                    }
                }
            }
        }
    }
}

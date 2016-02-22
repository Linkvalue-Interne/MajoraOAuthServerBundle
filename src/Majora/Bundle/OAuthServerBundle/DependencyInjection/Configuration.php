<?php

namespace Majora\Bundle\OAuthServerBundle\DependencyInjection;

use Majora\Component\OAuth\Entity\AccessToken;
use Majora\Component\OAuth\Entity\RefreshToken;
use Majora\Component\OAuth\Model\AccessTokenInterface;
use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Majora OAuth server bundle semantical configuration class.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @see http://symfony.com/doc/current/components/config/definition.html
     */
    public function getConfigTreeBuilder()
    {
        $supportedDrivers = array('orm');

        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('majora_oauth_server')
            ->children()
                ->scalarNode('db_driver')
                    ->validate()
                        ->ifNotInArray($supportedDrivers)
                        ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
                    ->end()
                ->end()
                ->scalarNode('secret')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('access_token')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('ttl')
                            ->defaultValue(AccessTokenInterface::DEFAULT_TTL)
                        ->end()
                        ->scalarNode('class')
                            ->cannotBeEmpty()
                            ->defaultValue(AccessToken::class)
                            ->validate()
                                ->ifTrue(function ($accessTokenClass) {
                                    return !(
                                        class_exists($accessTokenClass, true)
                                        && (new \ReflectionClass($accessTokenClass))
                                            ->implementsInterface(AccessTokenInterface::class)
                                    );
                                })
                                ->thenInvalid('Provided access_token configuration has to be a valid class which implements Majora\Component\OAuth\Model\AccessTokenInterface.')
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('refresh_token')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('ttl')
                            ->defaultValue(RefreshTokenInterface::DEFAULT_TTL)
                        ->end()
                        ->scalarNode('class')
                            ->cannotBeEmpty()
                            ->defaultValue(RefreshToken::class)
                            ->validate()
                                ->ifTrue(function ($refreshTokenClass) {
                                    return !(
                                        class_exists($refreshTokenClass, true)
                                        && (new \ReflectionClass($refreshTokenClass))
                                        ->implementsInterface(RefreshTokenInterface::class)
                                    );
                                })
                                ->thenInvalid('Provided refresh_token configuration has to be a valid class which implements Majora\Component\OAuth\Model\RefreshTokenInterface.')
                            ->end()
                        ->end()
                        ->scalarNode('loader')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('application')
                    ->children()
                        ->scalarNode('loader')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('account')
                    ->children()
                        ->scalarNode('loader')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

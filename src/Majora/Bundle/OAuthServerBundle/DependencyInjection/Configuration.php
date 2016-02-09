<?php

namespace Majora\Bundle\OAuthServerBundle\DependencyInjection;

use Majora\Component\OAuth\Entity\AccessToken;
use Majora\Component\OAuth\Model\AccessTokenInterface;
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
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('majora_oauth_server')
            ->children()
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

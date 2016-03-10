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
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('majora_oauth_server')
            ->children()
                ->scalarNode('secret')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->append($this->createTokenNode(
                    'access_token',
                    AccessToken::class,
                    AccessTokenInterface::class,
                    AccessTokenInterface::DEFAULT_TTL
                ))
                ->append($this->createTokenNode(
                    'refresh_token',
                    RefreshToken::class,
                    RefreshTokenInterface::class,
                    RefreshTokenInterface::DEFAULT_TTL
                ))
                ->arrayNode('application')
                    ->isRequired()
                    ->children()
                        ->append($this->createDriverStrategyNode('loader'))
                        ->append($this->createDriverStrategyNode('repository'))
                    ->end()
                ->end()
                ->arrayNode('account')
                    ->isRequired()
                    ->children()
                        ->append($this->createDriverStrategyNode('loader'))
                        ->append($this->createDriverStrategyNode('repository'))
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Create a token configuration node.
     *
     * @param string $tokenName
     * @param string $defaultClass
     * @param string $tokenInterface
     * @param string $defaultTtl
     *
     * @return Node
     */
    private function createTokenNode($tokenName, $defaultClass, $tokenInterface, $defaultTtl)
    {
        $builder = new TreeBuilder();
        $node = $builder->root($tokenName);
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->integerNode('ttl')
                    ->defaultValue($defaultTtl)
                ->end()
                ->scalarNode('class')
                    ->cannotBeEmpty()
                    ->defaultValue($defaultClass)
                    ->validate()
                        ->ifTrue(function ($accessTokenClass) use ($tokenInterface) {
                            return !(
                                class_exists($accessTokenClass, true)
                                && (new \ReflectionClass($accessTokenClass))
                                    ->implementsInterface($tokenInterface)
                            );
                        })
                        ->thenInvalid(sprintf(
                            'Provided access_token configuration has to be a valid class which implements %s.',
                            $tokenInterface
                        ))
                    ->end()
                ->end()
                ->append($this->createDriverStrategyNode('loader'))
                ->append($this->createDriverStrategyNode('repository'))
            ->end()
        ;

        return $node;
    }

    private function createDriverStrategyNode($strategyName)
    {
        $builder = new TreeBuilder();
        $node = $builder->root($strategyName);
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('id')->end()
                ->arrayNode('orm')
                    ->children()
                        ->scalarNode('entity_manager')
                            ->defaultValue('default')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('empty')->defaultNull()->end()
            ->end()
            ->validate()
                ->always()
                ->then(function ($v) {
                    if (count($v) == 2) {
                        unset($v['empty']);
                    }
                    if (count($v) > 1) {
                        throw new \InvalidArgumentException(
                            'You provided too much drivers, you have to choose only one.'
                        );
                    }

                    return $v;
                })
            ->end()
        ;

        return $node;
    }
}

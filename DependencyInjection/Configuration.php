<?php

namespace Majora\OAuthServerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('majora_oauth_server');

        $rootNode->children()
            ->arrayNode('storage')
                ->isRequired()
                ->children()
                    ->scalarNode('access_token')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('auth_code')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('client')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('refresh_token')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('user')->isRequired()->cannotBeEmpty()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}

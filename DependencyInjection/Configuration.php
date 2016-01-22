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
                ->children()
                    ->scalarNode('access_token')->cannotBeEmpty()->end()
                    ->scalarNode('auth_code')->cannotBeEmpty()->end()
                    ->scalarNode('client')->cannotBeEmpty()->end()
                    ->scalarNode('refresh_token')->cannotBeEmpty()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}

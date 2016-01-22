<?php

/*
 * This file is part of the MajoraOAuthServerBundle package.
 *
 * (c) Raphael De Freitas <raphael@de-freitas.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Majora\OAuthServerBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AliasingCompilerPass.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class AliasingCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $aliasMap = [
            'majora_oauth_server.storage.access_token' => 'oauth2.storage.access_token',
            'majora_oauth_server.storage.client' => 'oauth2.storage.client',
        ];

        foreach ($aliasMap as $parameter => $alias) {
            if ($container->hasParameter($parameter)) {
                if ($container->hasDefinition($container->getParameter($parameter))) {
                    $container->setAlias($alias, $container->getParameter($parameter));
                }
            }
        }
    }
}

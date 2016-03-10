<?php

namespace Majora\Bundle\OAuthServerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to register custom grant extensions.
 */
class RegisterGrantExtensionsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $extensionsTags = $container->findTaggedServiceIds('majora.oauth.grant_extension');
        $serverDefinition = $container->getDefinition('majora.oauth.server');

        foreach ($extensionsTags as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['grant_type'])) {
                    throw new \InvalidArgumentException(sprintf(
                        '"grant_type" attribute is required to use "majora.oauth.grant_extension" tag into service "%s".',
                        $serviceId
                    ));
                }

                $serverDefinition->addMethodCall('registerGrantExtension', array(
                    $attributes['grant_type'],
                    new Reference($serviceId),
                ));
            }
        }
    }
}

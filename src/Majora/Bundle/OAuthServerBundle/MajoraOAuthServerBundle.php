<?php

namespace Majora\Bundle\OAuthServerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;

class MajoraOAuthServerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return $this->createContainerExtension();
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $this->registerMappings($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function registerMappings(ContainerBuilder $container)
    {
        $ormCompilerClass = 'Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass';

        $mappings = array(
            realpath(__DIR__.'/Resources/config/mapping/ORM') => 'Majora\Component\OAuth\Entity',
        );

        if (class_exists($ormCompilerClass)) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createYamlMappingDriver($mappings, array(), 'majora_oauth_server.driver.orm'));
        }
    }
}

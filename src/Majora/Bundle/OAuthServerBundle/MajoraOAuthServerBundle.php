<?php

namespace Majora\Bundle\OAuthServerBundle;

use Majora\Bundle\OAuthServerBundle\DependencyInjection\Compiler\RegisterGrantExtensionsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

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
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterGrantExtensionsPass());
    }
}

<?php

namespace Majora\OAuthServerBundle;

use Majora\OAuthServerBundle\DependencyInjection\CompilerPass\AliasingCompilerPass;
use Majora\OAuthServerBundle\DependencyInjection\MajoraOAuthServerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MajoraOAuthServerBundle.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class MajoraOAuthServerBundle extends Bundle
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->extension = new MajoraOAuthServerExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AliasingCompilerPass());
    }
}

<?php

namespace Majora\OAuthServerBundle;

use Majora\OAuthServerBundle\DependencyInjection\MajoraOAuthServerExtension;
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
}

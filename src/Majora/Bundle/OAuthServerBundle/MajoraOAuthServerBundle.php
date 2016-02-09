<?php

namespace Majora\Bundle\OAuthServerBundle;

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
}

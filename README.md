# MajoraOAuthServerBundle

## Implementation example

### TokenController

```php
<?php

namespace AppBundle\Controller;

use OAuth2\Server;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TokenController extends Controller
{
    public function postAction(Request $request)
    {
        /** @var Server $server */
        $server = $this->get('oauth2.server');

        $request = \Majora\OAuthServerBundle\Bridge\Request::createFromRequest($request);
        /** @var \Majora\OAuthServerBundle\Bridge\Response $response */
        $response = $this->get('oauth2.response');

        return $server->handleTokenRequest($request, $response);
    }
}

```


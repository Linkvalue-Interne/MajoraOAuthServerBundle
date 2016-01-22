<?php

namespace Majora\OAuthServerBundle\Controller;

use Majora\OAuthServerBundle\Bridge\Request;
use Majora\OAuthServerBundle\Bridge\Response;
use OAuth2\Server;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TokenController.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class TokenController extends Controller
{
    public function postAction()
    {
        /** @var Server $oauthServer */
        $oauthServer = $this->get('oauth2.server');
        /** @var Request $request */
        $request = $this->get('oauth2.request');
        /** @var Response $response */
        $response = $this->get('oauth2.response');

        return $oauthServer->handleTokenRequest($request, $response);
    }
}

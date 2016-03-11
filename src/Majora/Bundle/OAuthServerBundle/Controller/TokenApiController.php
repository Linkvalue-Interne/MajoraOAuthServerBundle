<?php

namespace Majora\Bundle\OAuthServerBundle\Controller;

use Majora\Bundle\FrameworkExtraBundle\Controller\RestApiControllerTrait;
use Majora\Component\OAuth\Exception\InvalidGrantException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for oAuth tokens web cases.
 */
class TokenApiController extends Controller
{
    use RestApiControllerTrait;

    /**
     * Create a new access_token from given request throught oAuth server.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postAction(Request $request)
    {
        try {
            return $this->createJsonResponse($this->get('oauth.server')->grant(
                $this->getRequestData($request, 'snakelize')
            ));
        } catch (InvalidGrantException $e) {
            return new JsonResponse('Unavailable to create access token : bad credentials.', 401);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse('Invalid or missing fields for grant type.', 400);
        }
    }
}

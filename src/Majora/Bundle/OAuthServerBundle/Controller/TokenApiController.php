<?php

namespace Majora\Bundle\OAuthServerBundle\Controller;

use Majora\Bundle\FrameworkExtraBundle\Controller\RestApiControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        // UndefinedOptionsException
        // MissingOptionsException

        // $badRequestException = $this->createJsonBadRequestResponse();
        // $accessDeniedException = new AccessDeniedHttpException();

        // try {
            $accessToken = $this->get('oauth.server')->grant(
                $this->getRequestData($request, 'snakelize')
            );
        // } catch (\Exception $e) {

        // }

        return $this->createJsonResponse($accessToken);
    }
}

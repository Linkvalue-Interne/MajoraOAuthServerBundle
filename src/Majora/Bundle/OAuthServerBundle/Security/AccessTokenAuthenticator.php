<?php

namespace Majora\Bundle\OAuthServerBundle\Security;

use Majora\Component\OAuth\Exception\InvalidAccessTokenException;
use Majora\Component\OAuth\Server\Server as OAuthServer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * AccessToken authenticator class using Guard Symfony component and OAuth server.
 *
 * @link https://knpuniversity.com/screencast/guard/api-token
 */
class AccessTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var OAuthServer
     */
    protected $oauthServer;

    /**
     * @var AccessTokenInterface
     */
    protected $currentAccessToken;

    /**
     * Construct.
     *
     * @param OAuthServer $oauthServer
     */
    public function __construct(OAuthServer $oauthServer)
    {
        $this->oauthServer = $oauthServer;
    }

    /**
     * @see GuardAuthenticatorInterface::getCredentials()
     */
    public function getCredentials(Request $request)
    {
        switch (true) {

            // token through query params
            case $request->query->has('access_token') :
                $hash = $request->query->get('access_token');
            break;

            // token through headers
            case $request->headers->has('Authorization') :
                if (!preg_match('#^Bearer ([\w]+)$#', $request->headers->get('Authorization'), $matches)) {
                    break; // bad Authorization format
                }

                $hash = $matches[1];
            break;

            // otherwise auth failed
            default:
                return;
        }

        try {
            return $this->currentAccessToken = $this->oauthServer->check($hash);
        } catch (InvalidAccessTokenException $e) {
            // log there
        }
    }

    /**
     * @see GuardAuthenticatorInterface::getUser()
     */
    public function getUser($accessToken, UserProviderInterface $userProvider)
    {
        return $accessToken->getAccount();
    }

    /**
     * @see GuardAuthenticatorInterface::checkCredentials()
     */
    public function checkCredentials($accessToken, UserInterface $user)
    {
        // test here if access token is valid (expiration date etc...)
        // or api limit isnt crossed
        // or anonymous allowed
        // etc...
    }

    /**
     * @see GuardAuthenticatorInterface::onAuthenticationSuccess()
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $request->query->remove('access_token');
        $request->headers->remove('Authorization');
        $request->attributes->set('access_token', $this->currentAccessToken);
    }

    /**
     * @see GuardAuthenticatorInterface::onAuthenticationFailure()
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(
            array('message' => 'OAuth authentication required.'),
            403
        );
    }

    /**
     * @see GuardAuthenticatorInterface::onAuthenticationFailure()
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        // if anonymous granted
    }

    /**
     * @see GuardAuthenticatorInterface::supportsRememberMe()
     */
    public function supportsRememberMe()
    {
        return false;
    }
}

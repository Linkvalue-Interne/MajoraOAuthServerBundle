<?php

namespace Majora\Component\OAuth\Server;

use Majora\Component\OAuth\Entity\AccessToken;
use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Entity\RefreshToken;
use Majora\Component\OAuth\Event\AccessTokenEvent;
use Majora\Component\OAuth\Event\AccessTokenEvents;
use Majora\Component\OAuth\Event\RefreshTokenEvent;
use Majora\Component\OAuth\Event\RefreshTokenEvents;
use Majora\Component\OAuth\Exception\InvalidGrantException;
use Majora\Component\OAuth\Generator\RandomTokenGenerator;
use Majora\Component\OAuth\GrantType\GrantExtensionInterface;
use Majora\Component\OAuth\Loader\ApplicationLoaderInterface;
use Majora\Component\OAuth\Model\AccessTokenInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * OAuth server main class.
 */
class Server
{
    /**
     * @var GrantExtensionInterface[]
     */
    protected $grantExtensions;

    /**
     * @var ApplicationLoaderInterface
     */
    protected $applicationLoader;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var RandomTokenGenerator
     */
    protected $randomTokenGenerator;

    /**
     * @var int
     */
    protected $accessTokenTtl;

    /**
     * @var string
     */
    protected $accessTokenClassName;

    /**
     * @var int
     */
    protected $refreshTokenTtl;

    /**
     * @var string
     */
    protected $refreshTokenClassName;

    /**
     * Construct.
     *
     * @param EventDispatcherInterface   $eventDispatcher
     * @param ApplicationLoaderInterface $applicationLoader
     * @param int                        $accessTokenTtl
     * @param string                     $accessTokenClassName
     * @param int                        $refreshTokenTtl
     * @param string                     $refreshTokenClassName
     * @param RandomTokenGenerator       $randomTokenGenerator
     * @param array                      $grantExtensions
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ApplicationLoaderInterface $applicationLoader,
        $accessTokenTtl,
        $accessTokenClassName,
        $refreshTokenTtl,
        $refreshTokenClassName,
        RandomTokenGenerator $randomTokenGenerator,
        array $grantExtensions = array()
    ) {
        $this->applicationLoader = $applicationLoader;
        $this->eventDispatcher = $eventDispatcher;
        $this->randomTokenGenerator = $randomTokenGenerator;

        $this->accessTokenTtl = $accessTokenTtl ?: AccessTokenInterface::DEFAULT_TTL;
        $this->accessTokenClassName = $accessTokenClassName ?: AccessToken::class;

        $this->refreshTokenTtl = $refreshTokenTtl ?: RefreshTokenInterface::DEFAULT_TTL;
        $this->refreshTokenClassName = $refreshTokenClassName ?: RefreshToken::class;

        $this->grantExtensions = array();
        foreach ($grantExtensions as $grantType => $extension) {
            $this->registerGrantExtension($grantType, $extension);
        }
    }

    /**
     * Register an extension under given grant type.
     *
     * @param string                  $grantType
     * @param GrantExtensionInterface $extension
     */
    public function registerGrantExtension($grantType, GrantExtensionInterface $extension)
    {
        $this->grantExtensions[$grantType] = $extension;
    }

    /**
     * Validate given request parameters and build a
     * LoginAttempt object with it.
     *
     * @param array $data
     * @param array $headers
     * @param array $query
     *
     * @return LoginAttempt
     */
    protected function createLoginAttempt(array $data, array $headers, array $query)
    {
        // validate grant_type manually (needed to guess specialized option resolver)
        if (empty($data['grant_type'])) {
            throw new \InvalidArgumentException('Any grant_type given.');
        }
        $grantType = $data['grant_type'];
        if (!isset($this->grantExtensions[$grantType])) {
            throw new \InvalidArgumentException('Given grant_type is invalid.');
        }

        // create option resolver
        $requestResolver = new OptionsResolver();
        $requestResolver->setRequired(array(
            'client_secret',
            'client_api_key',
            'grant_type',
        ));
        $this->grantExtensions[$grantType]->configureRequestParameters(
            $requestResolver
        );

        return new LoginAttempt(
            $query,
            $requestResolver->resolve($data),
            $headers
        );
    }

    /**
     * Loads application for given login attempt.
     *
     * @param LoginAttempt $loginAttempt
     *
     * @return ApplicationInterface
     *
     * @throws InvalidGrantException
     */
    protected function loadApplication(LoginAttempt $loginAttempt)
    {
        // retrieve Application
        if (!$application = $this->applicationLoader->retrieveByApiKeyAndSecret(
            $loginAttempt->getData('client_api_key'),
            $loginAttempt->getData('client_secret')
        )) {
            throw new InvalidGrantException(
                $loginAttempt,
                'Any application found for given api_key / secret.'
            );
        }

        return $application;
    }

    /**
     * Runs grant extension to load accounts.
     *
     * @param ApplicationInterface $application
     * @param LoginAttempt         $loginAttempt
     *
     * @return AccountInterface
     *
     * @throws \InvalidArgumentException
     * @throws UnknownGrantTypeException
     */
    protected function  loadAccount(
        ApplicationInterface $application,
        LoginAttempt $loginAttempt
    ) {
        // run grant extension
        return $this->grantExtensions[$loginAttempt->getData('grant_type')]->grant(
            $application,
            $loginAttempt
        );
    }

    /**
     * Grant given credentials, or throws an exception if invalid
     * credentials for application or account.
     *
     * @param array $data    login request data
     * @param array $headers optionnal login request headers
     * @param array $query   optionnal login request query
     *
     * @return AccessTokenInterface
     */
    public function grant(array $data, array $headers = array(), array $query = array())
    {
        // create and validate login attempt from given data
        $loginAttempt = $this->createLoginAttempt(
            $data, $headers, $query
        );

        // load application / account
        $account = $this->loadAccount(
            $application = $this->loadApplication($loginAttempt),
            $loginAttempt
        );

        // event call
        $refreshToken = null;
        if (in_array('refresh_token', $application->getAllowedGrantTypes()) && array_key_exists('refresh_token', $this->grantExtensions)) {
            $this->eventDispatcher->dispatch(
                RefreshTokenEvents::MAJORA_REFRESH_TOKEN_CREATED,
                new RefreshTokenEvent(
                    $refreshToken = new $this->refreshTokenClassName(
                        $application,
                        $account,
                        $this->refreshTokenTtl,
                        $this->randomTokenGenerator->generate('refresh_token')
                    )
                )
            );
        }

        $this->eventDispatcher->dispatch(
            AccessTokenEvents::MAJORA_ACCESS_TOKEN_CREATED,
            new AccessTokenEvent(
                $accessToken = new $this->accessTokenClassName(
                    $application,
                    $account,
                    $this->accessTokenTtl,
                    $this->randomTokenGenerator->generate('access_token'),
                    $refreshToken
                )
            )
        );

        return $accessToken;
    }
}

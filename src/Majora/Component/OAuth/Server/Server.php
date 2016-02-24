<?php

namespace Majora\Component\OAuth\Server;

use Majora\Component\OAuth\Entity\AccessToken;
use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Event\AccessTokenEvent;
use Majora\Component\OAuth\Event\AccessTokenEvents;
use Majora\Component\OAuth\Exception\InvalidGrantException;
use Majora\Component\OAuth\Generator\RandomTokenGenerator;
use Majora\Component\OAuth\GrantType\GrantExtensionInterface;
use Majora\Component\OAuth\Loader\ApplicationLoaderInterface;
use Majora\Component\OAuth\Model\AccessTokenInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Majora\Framework\Model\EntityCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * OAuth server main class.
 */
class Server
{
    /**
     * @var EntityCollection
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
     * @var array
     */
    protected $tokenOptions;

    /**
     * Construct.
     *
     * @param EventDispatcherInterface   $eventDispatcher
     * @param ApplicationLoaderInterface $applicationLoader
     * @param RandomTokenGenerator       $randomTokenGenerator
     * @param array                      $tokenOptions
     * @param array                      $grantExtensions
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ApplicationLoaderInterface $applicationLoader,
        RandomTokenGenerator $randomTokenGenerator,
        array $tokenOptions,
        array $grantExtensions = array()
    ) {
        $this->applicationLoader = $applicationLoader;
        $this->eventDispatcher = $eventDispatcher;
        $this->randomTokenGenerator = $randomTokenGenerator;

        $tokenOptionsResolver = new OptionsResolver();
        $tokenOptionsResolver->setDefaults(array(
            'access_token_class' => AccessToken::class,
            'access_token_ttl' => AccessTokenInterface::DEFAULT_TTL,
            'refresh_token_class' => RefreshTokenInterface::DEFAULT_TTL,
            'refresh_token_ttl' => RefreshTokenInterface::DEFAULT_TTL,
        ));

        $this->tokenOptions = $tokenOptionsResolver->resolve($tokenOptions);

        $this->grantExtensions = new EntityCollection();
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
        $this->grantExtensions->set($grantType, $extension);
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
        if (!$this->grantExtensions->containsKey($grantType)) {
            throw new \InvalidArgumentException('Given grant_type is invalid.');
        }

        // create option resolver
        $requestResolver = new OptionsResolver();
        $requestResolver->setRequired(array(
            'client_secret',
            'client_api_key',
            'grant_type',
        ));
        $this->grantExtensions->get($grantType)
            ->configureRequestParameters($requestResolver)
        ;

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
    protected function loadAccount(
        ApplicationInterface $application,
        LoginAttempt $loginAttempt
    ) {
        // run grant extension result
        return $this->grantExtensions
            ->get($loginAttempt->getData('grant_type'))
            ->grant($application, $loginAttempt)
        ;
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
        $this->eventDispatcher->dispatch(
            AccessTokenEvents::MAJORA_ACCESS_TOKEN_CREATED,
            new AccessTokenEvent(

                // access token generation
                $accessToken = new $this->tokenOptions['access_token_class'](
                    $application,
                    $account,
                    $this->tokenOptions['access_token_ttl'],
                    $this->randomTokenGenerator->generate('access_token'),

                    // refresh token generation only if necessary
                    in_array('refresh_token', $application->getAllowedGrantTypes())
                    && $this->grantExtensions->containsKey('refresh_token') ?
                        new $this->tokenOptions['refresh_token_class'](
                            $application,
                            $account,
                            $this->tokenOptions['refresh_token_ttl'],
                            $this->randomTokenGenerator->generate('refresh_token')
                        ) :
                        null
                )
            )
        );

        return $accessToken;
    }
}

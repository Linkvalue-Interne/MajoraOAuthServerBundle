<?php

namespace Majora\Component\OAuth\GrantType;

use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Exception\InvalidGrantException;
use Majora\Component\OAuth\Loader\AccountLoaderInterface;
use Majora\Component\OAuth\Loader\RefreshTokenLoaderInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Built-in extension for refresh token.
 * Required data :
 *     - refresh_token.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class RefreshTokenGrantExtension implements GrantExtensionInterface
{
    /**
     * @var AccountLoaderInterface
     */
    protected $accountLoader;

    /**
     * @var RefreshTokenLoaderInterface
     */
    protected $refreshTokenLoader;

    /**
     * Construct.
     *
     * @param AccountLoaderInterface      $accountLoader
     * @param RefreshTokenLoaderInterface $refreshTokenLoader
     */
    public function __construct(
        AccountLoaderInterface $accountLoader,
        RefreshTokenLoaderInterface $refreshTokenLoader
    ) {
        $this->accountLoader = $accountLoader;
        $this->refreshTokenLoaderrefreshTokenLoader = $refreshTokenLoader;
    }

    /**
     * @see GrantExtensionInterface::configureRequestParameters()
     */
    public function configureRequestParameters(OptionsResolver $requestResolver)
    {
        $requestResolver->setRequired(['refresh_token']);
    }

    /**
     * @see GrantExtensionInterface::grant()
     */
    public function grant(ApplicationInterface $application, LoginAttempt $loginAttempt)
    {
        // Retrieve RefreshToken
        if (!$refreshToken = $this->refreshTokenLoader->retrieveByHash(
            $loginAttempt->getData('refresh_token')
        )) {
            throw new InvalidGrantException(
                $loginAttempt,
                'RefreshToken not found.'
            );
        }

        // Checks refresh token
        if ($refreshToken->getApplication() != $application) {
            throw new InvalidGrantException(
                $loginAttempt,
                'Invalid RefreshToken for loaded account.'
            );
        }

        return $refreshToken->getAccount();
    }
}

<?php

namespace Majora\Component\OAuth\GrantType;

use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Exception\InvalidGrantException;
use Majora\Component\OAuth\Loader\AccountLoaderInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Built-in extension for user credential granting.
 * Required data :
 *     - username
 *     - password.
 */
class PasswordGrantExtension implements GrantExtensionInterface
{
    /**
     * @var AccountLoaderInterface
     */
    protected $accountLoader;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * Construct.
     *
     * @param AccountLoaderInterface       $accountLoader
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        AccountLoaderInterface $accountLoader,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->accountLoader = $accountLoader;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @see GrantExtensionInterface::configureRequestParameters()
     */
    public function configureRequestParameters(OptionsResolver $requestResolver)
    {
        $requestResolver->setRequired(array('username', 'password'));
    }

    /**
     * @see GrantExtensionInterface::grant()
     */
    public function grant(ApplicationInterface $application, LoginAttempt $loginAttempt)
    {
        // Retrieve Account
        if (!$account = $this->accountLoader->retrieveOnApplicationByUsername(
            $application,
            $loginAttempt->getData('username')
        )) {
            throw new InvalidGrantException(
                $loginAttempt,
                'Username not found on loaded application.'
            );
        }

        // Checks password
        if (!$this->passwordEncoder->isPasswordValid(
            $account,
            $loginAttempt->getData('password')
        )) {
            throw new InvalidGrantException(
                $loginAttempt,
                'Invalid password for loaded account.'
            );
        }

        return $account;
    }
}

<?php

namespace Majora\Component\OAuth\GrantType;

use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Built-in extension for client credentials granting.
 */
class ClientCredentialsGrantExtension implements GrantExtensionInterface
{
    /**
     * @see GrantExtensionInterface::configureRequestParameters()
     */
    public function configureRequestParameters(OptionsResolver $requestResolver)
    {
        // No need to require additional request parameters.
    }

    /**
     * @see GrantExtensionInterface::grant()
     */
    public function grant(ApplicationInterface $application, LoginAttempt $loginAttempt)
    {
        // No need to load an Account for this type of grant, the Application alone is enough.
        return null;
    }
}

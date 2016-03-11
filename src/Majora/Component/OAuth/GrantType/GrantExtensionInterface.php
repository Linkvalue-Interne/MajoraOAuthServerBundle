<?php

namespace Majora\Component\OAuth\GrantType;

use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface to implement on grant types related extension.
 */
interface GrantExtensionInterface
{
    /**
     * Configure handled request parameters handled by extension.
     *
     * @param OptionsResolver $requestResolver
     */
    public function configureRequestParameters(OptionsResolver $requestResolver);

    /**
     * Grants or deny given identity for this extension.
     *
     * @param ApplicationInterface $application
     * @param LoginAttempt         $loginAttempt
     *
     * @return AccountInterface
     *
     * @throws InvalidGrantException
     */
    public function grant(ApplicationInterface $application, LoginAttempt $loginAttempt);
}

<?php

namespace Majora\Component\OAuth\Exception;

use Majora\Component\OAuth\Entity\LoginAttempt;

/**
 * Custom exception class which have to be thrown into GrantExtensions
 * when given LoginAttempt is invalid.
 */
class InvalidGrantException extends \InvalidArgumentException
{
    /**
     * @var LoginAttempt
     */
    protected $loginAttempt;

    /**
     * Construct.
     *
     * @param LoginAttempt $loginAttempt
     */
    public function __construct(LoginAttempt $loginAttempt, $message = '', $code = null, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->loginAttempt = $loginAttempt;
    }

    /**
     * Return denied LoginAttempt object.
     *
     * @return LoginAttempt
     */
    public function getLoginAttempt()
    {
        return $this->loginAttempt;
    }
}

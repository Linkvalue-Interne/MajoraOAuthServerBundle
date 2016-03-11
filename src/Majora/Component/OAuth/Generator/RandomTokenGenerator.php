<?php

namespace Majora\Component\OAuth\Generator;

/**
 * Generate a token.
 *
 * @see inspired from https://github.com/FriendsOfSymfony/FOSOAuthServerBundle/blob/master/Util/Random.php
 */
class RandomTokenGenerator
{
    /**
     * @var string
     */
    protected $secret;

    /**
     * Construct.
     *
     * @param string $secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Generate a random token.
     *
     * @param string $intention contextual string
     *
     * @return string
     */
    public function generate($intention = 'm@J0raOaUth')
    {
        $bytes = false;
        if (function_exists('openssl_random_pseudo_bytes') && 0 !== stripos(PHP_OS, 'win')) {
            $bytes = openssl_random_pseudo_bytes(32, $strong);

            if (true !== $strong) {
                $bytes = false;
            }
        }

        // let's just hope we got a good seed
        if (false === $bytes) {
            $bytes = hash('sha512',
                sprintf('-[%s}\%s/{%s]-',
                    $intention,
                    uniqid(mt_rand(), true),
                    $this->secret
                ),
                true
            );
        }

        return str_pad(
            base_convert(bin2hex($bytes), 16, 36),
            50,
            rand(0, 9)
        );
    }
}

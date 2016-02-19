<?php

namespace Majora\Component\OAuth\Event;

/**
 * RefreshToken events reference class.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
final class RefreshTokenEvents
{
    /**
     * event fired when an refresh token is created.
     */
    const MAJORA_REFRESH_TOKEN_CREATED = 'majora.refresh_token.created';
}

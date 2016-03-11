<?php

namespace Majora\Component\OAuth\Model;

use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

/**
 * Symfony user interface extended with some oauth specific methods.
 */
interface AccountInterface extends SymfonyUserInterface
{
    /**
     * Return identity owner id.
     *
     * @return mixed
     */
    public function getOwnerId();
}

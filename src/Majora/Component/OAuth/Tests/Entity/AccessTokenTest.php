<?php
namespace Majora\Component\OAuth\Tests\Entity;

use Majora\Component\OAuth\Entity\AccessToken;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Model\RefreshTokenInterface;

/**
 * Class AccessTokenTest
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class AccessTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if the refresh token given in constructor is equals to returned by the getter.
     */
    public function testConstructorRefreshToken()
    {
        $application = $this->prophesize(ApplicationInterface::class);
        $application
            ->getSecret()
            ->willReturn('mock_secret')
            ->shouldBeCalled();

        $account = $this->prophesize(AccountInterface::class);
        $account
            ->getPassword()
            ->willReturn('mock_password')
            ->shouldBeCalled();

        $refreshToken = $this->prophesize(RefreshTokenInterface::class);

        $token = new AccessToken(
            $application->reveal(),
            $account->reveal(),
            42,
            null,
            null,
            $refreshToken->reveal()
        );

        $this->assertEquals($refreshToken->reveal(), $token->getRefreshToken());
    }

    /**
     * Test getScopes() method.
     */
    public function testScopes()
    {
        $application = $this->prophesize(ApplicationInterface::class);
        $application
            ->getSecret()
            ->willReturn('mock_secret')
            ->shouldBeCalled();

        $account = $this->prophesize(AccountInterface::class);
        $account
            ->getPassword()
            ->willReturn('mock_password')
            ->shouldBeCalled();

        $token = new AccessToken(
            $application->reveal(),
            $account->reveal()
        );

        $expectedScopes = [
            'default' => ['id', 'hash', 'refresh_token', 'expire_in', 'application@id', 'account@id'],
        ];
        $this->assertEquals($expectedScopes, $token->getScopes(), '', 0.0, 10, true);
    }
}

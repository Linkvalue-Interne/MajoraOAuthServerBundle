<?php

namespace Majora\Component\OAuth\Tests\GrantType;

use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Exception\InvalidGrantException;
use Majora\Component\OAuth\GrantType\RefreshTokenGrantExtension;
use Majora\Component\OAuth\Loader\AccountLoaderInterface;
use Majora\Component\OAuth\Loader\RefreshTokenLoaderInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Unit test class for Majora\Component\OAuth\GrantType\RefreshTokenGrantExtensionTest.
 */
class RefreshTokenGrantExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test configureRequestParameters() method.
     */
    public function testConfigureRequestParameters()
    {
        $optionsResolver = new OptionsResolver();
        $refreshTokenGrantExtension = new RefreshTokenGrantExtension(
            $this->prophesize(AccountLoaderInterface::class)->reveal(),
            $this->prophesize(RefreshTokenLoaderInterface::class)->reveal()
        );
        $refreshTokenGrantExtension->configureRequestParameters($optionsResolver);

        // Testing the required options
        $actualRequiredOptions = $optionsResolver->getRequiredOptions();
        $expectedRequiredOptions = ['refresh_token'];
        $this->assertEquals($expectedRequiredOptions, $actualRequiredOptions, '', $delta = 0.0, 10, true); // Not caring about keys

        // Testing the optional options
        $actualOptionalOptions = array_diff($optionsResolver->getDefinedOptions(), $actualRequiredOptions);
        $expectedOptionalOptions = [];
        $this->assertCount(0, array_diff($expectedOptionalOptions, $actualOptionalOptions));
    }

    /**
     * Test grant() method on success.
     */
    public function testSuccessGrant()
    {
        // Mocking AccountInterface
        $account = $this->prophesize(AccountInterface::class)->reveal();

        // Mocking ApplicationInterface
        $application = $this->prophesize(ApplicationInterface::class)->reveal();

        // Mocking RefreshToken
        $refreshToken = $this->prophesize(RefreshTokenInterface::class);
        $refreshToken->getAccount()
            ->willReturn($account)
            ->shouldBeCalled();
        $refreshToken->getApplication()
            ->willReturn($application)
            ->shouldBeCalled();

        // Mocking RefreshTokenLoader
        $refreshTokenLoader = $this->prophesize(RefreshTokenLoaderInterface::class);
        $refreshTokenLoader->retrieveByHash('hash_test')
            ->willReturn($refreshToken->reveal())
            ->shouldBeCalled();

        $refreshTokenGrantExtension = new RefreshTokenGrantExtension(
            $this->prophesize(AccountLoaderInterface::class)->reveal(),
            $refreshTokenLoader->reveal()
        );

        // Mocking LoginAttempt
        $loginAttempt = $this->prophesize(LoginAttempt::class);
        $loginAttempt->getData('refresh_token')
            ->willReturn('hash_test')
            ->shouldBeCalled();

        $actualAccount = $refreshTokenGrantExtension->grant(
            $application,
            $loginAttempt->reveal()
        );

        $this->assertSame($account, $actualAccount);
    }

    /**
     * Test grant() when it fails loading a refresh token.
     */
    public function testGrantFailingTokenLoading()
    {
        // Mocking ApplicationInterface
        $application = $this->prophesize(ApplicationInterface::class)->reveal();

        // Mocking RefreshTokenLoader
        $refreshTokenLoader = $this->prophesize(RefreshTokenLoaderInterface::class);
        $refreshTokenLoader->retrieveByHash('hash_test')
            ->willReturn(null)
            ->shouldBeCalled();

        $refreshTokenGrantExtension = new RefreshTokenGrantExtension(
            $this->prophesize(AccountLoaderInterface::class)->reveal(),
            $refreshTokenLoader->reveal()
        );

        // Mocking LoginAttempt
        $loginAttempt = $this->prophesize(LoginAttempt::class);
        $loginAttempt->getData('refresh_token')
            ->willReturn('hash_test')
            ->shouldBeCalled();

        $this->expectException(InvalidGrantException::class);
        $this->expectExceptionMessage('RefreshToken not found.');

        $refreshTokenGrantExtension->grant(
            $application,
            $loginAttempt->reveal()
        );
    }

    /**
     * Test grant() when it fails the application comparison.
     */
    public function testGrantFailingApplicationValidation()
    {
        // Mocking RefreshToken
        $refreshToken = $this->prophesize(RefreshTokenInterface::class);
        $refreshToken->getApplication()
            ->willReturn(null)
            ->shouldBeCalled();

        // Mocking RefreshTokenLoader
        $refreshTokenLoader = $this->prophesize(RefreshTokenLoaderInterface::class);
        $refreshTokenLoader->retrieveByHash('hash_test')
            ->willReturn($refreshToken->reveal())
            ->shouldBeCalled();

        $refreshTokenGrantExtension = new RefreshTokenGrantExtension(
            $this->prophesize(AccountLoaderInterface::class)->reveal(),
            $refreshTokenLoader->reveal()
        );

        // Mocking LoginAttempt
        $loginAttempt = $this->prophesize(LoginAttempt::class);
        $loginAttempt->getData('refresh_token')
            ->willReturn('hash_test')
            ->shouldBeCalled();

        $this->expectException(InvalidGrantException::class);
        $this->expectExceptionMessage('Invalid RefreshToken for loaded account.');

        $refreshTokenGrantExtension->grant(
            $this->prophesize(ApplicationInterface::class)->reveal(),
            $loginAttempt->reveal()
        );
    }
}

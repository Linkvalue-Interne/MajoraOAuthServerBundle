<?php
namespace Majora\Component\OAuth\Tests\GrantType;

use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Exception\InvalidGrantException;
use Majora\Component\OAuth\GrantType\PasswordGrantExtension;
use Majora\Component\OAuth\Loader\AccountLoaderInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Prophecy\Argument;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Unit test class for Majora\Component\OAuth\GrantType\PasswordGrantTypeExtension.
 */
class PasswordGrantExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test configureRequestParameters() method.
     */
    public function testConfigureRequestParameters()
    {
        $optionsResolver = new OptionsResolver();
        $passwordGrantExtension = new PasswordGrantExtension(
            $this->prophesize(AccountLoaderInterface::class)->reveal(),
            $this->prophesize(UserPasswordEncoderInterface::class)->reveal()
        );
        $passwordGrantExtension->configureRequestParameters($optionsResolver);

        // Testing the required options
        $actualRequiredOptions = $optionsResolver->getRequiredOptions();
        $expectedRequiredOptions = ['password', 'username'];
        $this->assertEquals($expectedRequiredOptions, $actualRequiredOptions, '', $delta = 0.0, 10, true); // Not caring about keys

        // Testing the optional options
        $actualOptionalOptions = array_diff($optionsResolver->getDefinedOptions(), $actualRequiredOptions);
        $expectedOptionalOptions = [];
        $this->assertCount(0, array_diff($expectedOptionalOptions, $actualOptionalOptions));
    }

    /**
     * Test grant() method on success
     */
    public function testSuccessGrant()
    {
        // Mocking AccountInterface
        $account = $this->prophesize(AccountInterface::class)->reveal();

        // Mocking AccountLoader
        $accountLoader = $this->prophesize(AccountLoaderInterface::class);
        $accountLoader->retrieveOnApplicationByUsername(Argument::type(ApplicationInterface::class), 'username_test')
            ->willReturn($account)
            ->shouldBeCalled();

        // Mocking UserPasswordEncoderInterface
        $userPasswordEncoder = $this->prophesize(UserPasswordEncoderInterface::class);
        $userPasswordEncoder->isPasswordValid(Argument::type(UserInterface::class), 'password_test')
            ->willReturn(true)
            ->shouldBeCalled();

        $passwordGrantExtension = new PasswordGrantExtension(
            $accountLoader->reveal(),
            $userPasswordEncoder->reveal()
        );

        // Mocking LoginAttempt
        $loginAttempt = $this->prophesize(LoginAttempt::class);
        $loginAttempt->getData('username')
            ->willReturn('username_test')
            ->shouldBeCalled();
        $loginAttempt->getData('password')
            ->willReturn('password_test')
            ->shouldBeCalled();

        $actualAccount = $passwordGrantExtension->grant(
            $this->prophesize(ApplicationInterface::class)->reveal(),
            $loginAttempt->reveal()
        );

        $this->assertSame($account, $actualAccount);
    }

    /**
     * Test grant() when it fails loading an account.
     */
    public function testGrantFailingAccountLoading()
    {
        // Mocking AccountLoader
        $accountLoader = $this->prophesize(AccountLoaderInterface::class);
        $accountLoader->retrieveOnApplicationByUsername(Argument::type(ApplicationInterface::class), 'username_test')
            ->willReturn(null)
            ->shouldBeCalled();

        $passwordGrantExtension = new PasswordGrantExtension(
            $accountLoader->reveal(),
            $this->prophesize(UserPasswordEncoderInterface::class)->reveal()
        );

        // Mocking LoginAttempt
        $loginAttempt = $this->prophesize(LoginAttempt::class);
        $loginAttempt->getData('username')
            ->willReturn('username_test')
            ->shouldBeCalled();

        $this->expectException(InvalidGrantException::class);

        $passwordGrantExtension->grant(
            $this->prophesize(ApplicationInterface::class)->reveal(),
            $loginAttempt->reveal()
        );
    }

    /**
     * Test grant() when it fails to validate the password.
     */
    public function testGrantFailingPasswordValidation()
    {
        // Mocking AccountLoader
        $accountLoader = $this->prophesize(AccountLoaderInterface::class);
        $accountLoader->retrieveOnApplicationByUsername(Argument::type(ApplicationInterface::class), 'username_test')
            ->willReturn($this->prophesize(AccountInterface::class)->reveal())
            ->shouldBeCalled();

        // Mocking UserPasswordEncoderInterface
        $userPasswordEncoder = $this->prophesize(UserPasswordEncoderInterface::class);
        $userPasswordEncoder->isPasswordValid(Argument::type(UserInterface::class), 'password_test')
            ->willReturn(false)
            ->shouldBeCalled();

        $passwordGrantExtension = new PasswordGrantExtension(
            $accountLoader->reveal(),
            $userPasswordEncoder->reveal()
        );

        // Mocking LoginAttempt
        $loginAttempt = $this->prophesize(LoginAttempt::class);
        $loginAttempt->getData('username')
            ->willReturn('username_test')
            ->shouldBeCalled();
        $loginAttempt->getData('password')
            ->willReturn('password_test')
            ->shouldBeCalled();


        $this->expectException(InvalidGrantException::class);

        $passwordGrantExtension->grant(
            $this->prophesize(ApplicationInterface::class)->reveal(),
            $loginAttempt->reveal()
        );
    }
}

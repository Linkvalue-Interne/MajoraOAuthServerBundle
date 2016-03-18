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

        $actualRequiredOptions = $optionsResolver->getRequiredOptions();
        $expectedRequiredOptions = ['password', 'username'];
        $this->assertEquals($expectedRequiredOptions, $actualRequiredOptions, '', $delta = 0.0, 10, true); // Not caring about keys
    }

    /**
     * Test grant() method on success
     */
    public function testSuccessGrant()
    {
        // Mocking AccountInterface
        /** @var AccountInterface $account */
        $account = $this->prophesize(AccountInterface::class)->reveal();

        // Mocking AccountLoaderInterface
        $accountLoaderMock = $this->prophesize(AccountLoaderInterface::class);
        $accountLoaderMock
            ->retrieveOnApplicationByUsername(Argument::type(ApplicationInterface::class), 'username_test')
            ->willReturn($account)
            ->shouldBeCalled();
        /** @var AccountLoaderInterface $accountLoader */
        $accountLoader = $accountLoaderMock->reveal();

        // Mocking UserPasswordEncoderInterface
        $userPasswordEncoderMock = $this->prophesize(UserPasswordEncoderInterface::class);
        $userPasswordEncoderMock
            ->isPasswordValid(Argument::type(UserInterface::class), 'password_test')
            ->willReturn(true)
            ->shouldBeCalled();
        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $userPasswordEncoderMock->reveal();

        $passwordGrantExtension = new PasswordGrantExtension($accountLoader, $userPasswordEncoder);

        // Mocking ApplicationInterface
        $applicationMock = $this->prophesize(ApplicationInterface::class);
        /** @var ApplicationInterface $application */
        $application = $applicationMock->reveal();

        // Mocking LoginAttempt
        $loginAttemptMock = $this->prophesize(LoginAttempt::class);
        $loginAttemptMock
            ->getData('username')
            ->willReturn('username_test')
            ->shouldBeCalled();
        $loginAttemptMock
            ->getData('password')
            ->willReturn('password_test')
            ->shouldBeCalled();
        /** @var LoginAttempt $loginAttempt */
        $loginAttempt = $loginAttemptMock->reveal();

        $actualAccount = $passwordGrantExtension->grant($application, $loginAttempt);
        $this->assertSame($account, $actualAccount);
    }

    /**
     * Test grant() when it fails loading an account.
     */
    public function testGrantFailingAccountLoading()
    {
        // Mocking AccountLoaderInterface
        $accountLoaderMock = $this->prophesize(AccountLoaderInterface::class);
        $accountLoaderMock
            ->retrieveOnApplicationByUsername(Argument::type(ApplicationInterface::class), 'username_test')
            ->willReturn(null)
            ->shouldBeCalled();
        /** @var AccountLoaderInterface $accountLoader */
        $accountLoader = $accountLoaderMock->reveal();

        // Mocking UserPasswordEncoderInterface
        $userPasswordEncoderMock = $this->prophesize(UserPasswordEncoderInterface::class);
        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $userPasswordEncoderMock->reveal();

        $passwordGrantExtension = new PasswordGrantExtension($accountLoader, $userPasswordEncoder);

        // Mocking ApplicationInterface
        $applicationMock = $this->prophesize(ApplicationInterface::class);
        /** @var ApplicationInterface $application */
        $application = $applicationMock->reveal();

        // Mocking LoginAttempt
        $loginAttemptMock = $this->prophesize(LoginAttempt::class);
        $loginAttemptMock
            ->getData('username')
            ->willReturn('username_test')
            ->shouldBeCalled();
        /** @var LoginAttempt $loginAttempt */
        $loginAttempt = $loginAttemptMock->reveal();

        $this->expectException(InvalidGrantException::class);
        $passwordGrantExtension->grant($application, $loginAttempt);
    }

    /**
     * Test grant() when it fails to validate the password.
     */
    public function testGrantFailingPasswordValidation()
    {
        // Mocking AccountInterface
        /** @var AccountInterface $account */
        $account = $this->prophesize(AccountInterface::class)->reveal();

        // Mocking AccountLoaderInterface
        $accountLoaderMock = $this->prophesize(AccountLoaderInterface::class);
        $accountLoaderMock
            ->retrieveOnApplicationByUsername(Argument::type(ApplicationInterface::class), 'username_test')
            ->willReturn($account)
            ->shouldBeCalled();
        /** @var AccountLoaderInterface $accountLoader */
        $accountLoader = $accountLoaderMock->reveal();

        // Mocking UserPasswordEncoderInterface
        $userPasswordEncoderMock = $this->prophesize(UserPasswordEncoderInterface::class);
        $userPasswordEncoderMock
            ->isPasswordValid(Argument::type(UserInterface::class), 'password_test')
            ->willReturn(false)
            ->shouldBeCalled();
        /** @var UserPasswordEncoderInterface $userPasswordEncoder */
        $userPasswordEncoder = $userPasswordEncoderMock->reveal();

        $passwordGrantExtension = new PasswordGrantExtension($accountLoader, $userPasswordEncoder);

        // Mocking ApplicationInterface
        $applicationMock = $this->prophesize(ApplicationInterface::class);
        /** @var ApplicationInterface $application */
        $application = $applicationMock->reveal();

        // Mocking LoginAttempt
        $loginAttemptMock = $this->prophesize(LoginAttempt::class);
        $loginAttemptMock
            ->getData('username')
            ->willReturn('username_test')
            ->shouldBeCalled();
        $loginAttemptMock
            ->getData('password')
            ->willReturn('password_test')
            ->shouldBeCalled();
        /** @var LoginAttempt $loginAttempt */
        $loginAttempt = $loginAttemptMock->reveal();
        $this->expectException(InvalidGrantException::class);

        $passwordGrantExtension->grant($application, $loginAttempt);
    }
}

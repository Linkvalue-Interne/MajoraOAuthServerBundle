<?php

namespace Majora\Component\OAuth\Tests\Server;

use Majora\Component\OAuth\Entity\AccessToken;
use Majora\Component\OAuth\Entity\Account;
use Majora\Component\OAuth\Entity\Application;
use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Entity\RefreshToken;
use Majora\Component\OAuth\Event\AccessTokenEvent;
use Majora\Component\OAuth\Event\AccessTokenEvents;
use Majora\Component\OAuth\Exception\InvalidGrantException;
use Majora\Component\OAuth\Generator\RandomTokenGenerator;
use Majora\Component\OAuth\GrantType\GrantExtensionInterface;
use Majora\Component\OAuth\Loader\ApplicationLoaderInterface;
use Majora\Component\OAuth\Model\AccessTokenInterface;
use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Majora\Component\OAuth\Server\Server;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Unit tests class for Majora\Component\OAuth\Server\Server.
 *
 * @see Majora\Component\OAuth\Server\Server
 */
class ServerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Success grant case provider.
     */
    public function grantSuccessCaseProvider()
    {
        return array(
            'default_settings' => $defaultCase = array(
                array(),
                AccessToken::class,
                array(
                    array(
                        'grant_type' => 'mocked_grant_type',
                        'client_api_key' => 'mocked_key',
                        'client_secret' => 'mocked_secret',
                    ),
                    array(),
                    array(),
                ),
                new AccessToken(new Application(), new Account(), AccessTokenInterface::DEFAULT_TTL, 'mocked_hash'),
            ),
            'ttl_settings' => array_replace_recursive($defaultCase, array(
                array('access_token_ttl' => 123456),
                AccessToken::class,
                array(),
                new AccessToken(new Application(), new Account(), 123456, 'mocked_hash'),
            )),
            'class_settings' => array_replace_recursive($defaultCase, array(
                array('access_token_class' => MockedAccessToken::class),
                MockedAccessToken::class,
                array(),
                new MockedAccessToken(new Application(), new Account(), AccessTokenInterface::DEFAULT_TTL, 'mocked_hash'),
            )),
        );
    }

    /**
     * Test grant success cases.
     *
     * @dataProvider grantSuccessCaseProvider
     */
    public function testSuccessGrant(
        $tokenOptions,
        $expectedTokensClass,
        array $grantArgs,
        AccessTokenInterface $expectedAccessToken
    ) {
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $eventDispatcher
            ->dispatch(AccessTokenEvents::MAJORA_ACCESS_TOKEN_CREATED, Argument::type(AccessTokenEvent::class))
            ->shouldBeCalled()
        ;
        $applicationLoader = $this->prophesize(ApplicationLoaderInterface::class);
        $applicationLoader
            ->retrieveByApiKeyAndSecret('mocked_key', 'mocked_secret')
            ->willReturn(new Application())
            ->shouldBeCalled()
        ;
        $randomTokenGenerator = $this->prophesize(RandomTokenGenerator::class);
        $randomTokenGenerator->generate('access_token')->willReturn('mocked_hash')->shouldBeCalled();
        $randomTokenGenerator->generate('refresh_token')->shouldNotBeCalled();

        $extension = $this->prophesize(GrantExtensionInterface::class);
        $extension
            ->configureRequestParameters(Argument::type(OptionsResolver::class))
            ->shouldBeCalled()
        ;
        $extension
            ->grant(Argument::type(Application::class), Argument::type(LoginAttempt::class))
            ->shouldBeCalled()
        ;

        $server = new Server(
            $eventDispatcher->reveal(),
            $applicationLoader->reveal(),
            $randomTokenGenerator->reveal(),
            $tokenOptions,
            array('mocked_grant_type' => $extension->reveal())
        );

        $accessToken = $server->grant(...$grantArgs);

        $this->assertInstanceOf($expectedTokensClass, $accessToken);
        $this->assertEquals($expectedAccessToken->getExpireIn(), $accessToken->getExpireIn());
        $this->assertNull($accessToken->getRefreshToken());
    }

    /**
     * Refresh token creation cases providers.
     */
    public function refreshTokenCreationCaseProvider()
    {
        return array(
            'default_settings' => $defaultCase = array(
                array(),
                RefreshToken::class,
                array(
                    array(
                        'grant_type' => 'mocked_grant_type',
                        'client_api_key' => 'mocked_key',
                        'client_secret' => 'mocked_secret',
                    ),
                    array(),
                    array(),
                ),
                new RefreshToken(
                    new Application(),
                    new Account(),
                    RefreshTokenInterface::DEFAULT_TTL,
                    'mocked_refresh_token_hash'
                ),
            ),
            'ttl_settings' => array_replace_recursive($defaultCase, array(
                array('refresh_token_ttl' => 654321),
                RefreshToken::class,
                array(),
                new RefreshToken(new Application(), new Account(), 654321, 'mocked_refresh_token_hash'),
            )),
            'class_settings' => array_replace_recursive($defaultCase, array(
                array('refresh_token_class' => MockedRefreshToken::class),
                MockedRefreshToken::class,
                array(),
                new MockedRefreshToken(
                    new Application(),
                    new Account(),
                    RefreshTokenInterface::DEFAULT_TTL,
                    'mocked_refresh_token_hash'
                ),
            )),
        );
    }

    /**
     * Tests refresh token creation on access token granting.
     *
     * @dataProvider refreshTokenCreationCaseProvider
     */
    public function testRefreshTokenOnGrant(
        $tokenOptions,
        $expectedTokensClass,
        array $grantArgs,
        RefreshTokenInterface $expectedRefreshToken
    ) {
        $applicationLoader = $this->prophesize(ApplicationLoaderInterface::class);
        $applicationLoader
            ->retrieveByApiKeyAndSecret('mocked_key', 'mocked_secret')
            ->willReturn((new Application())->setAllowedGrantTypes(array('refresh_token')))
            ->shouldBeCalled()
        ;
        $randomTokenGenerator = $this->prophesize(RandomTokenGenerator::class);
        $randomTokenGenerator->generate('access_token')
            ->willReturn('mocked_access_token_hash')
            ->shouldBeCalled()
        ;
        $randomTokenGenerator
            ->generate('refresh_token')
            ->willReturn('mocked_refresh_token_hash')
            ->shouldBeCalled()
        ;

        $server = new Server(
            $this->prophesize(EventDispatcherInterface::class)->reveal(),
            $applicationLoader->reveal(),
            $randomTokenGenerator->reveal(),
            $tokenOptions,
            array(
                'refresh_token' => $this->prophesize(GrantExtensionInterface::class)->reveal(),
                'mocked_grant_type' => $this->prophesize(GrantExtensionInterface::class)->reveal(),
            )
        );

        $accessToken = $server->grant(...$grantArgs);

        $this->assertInstanceOf($expectedTokensClass, $refreshToken = $accessToken->getRefreshToken());
        $this->assertEquals($expectedRefreshToken->getExpireIn(), $refreshToken->getExpireIn());
    }

    /**
     * Provider for error cases.
     */
    public function errorCaseProvider()
    {
        return array(
            'any_grant_type' => array(
                array(
                    array(
                        'client_api_key' => 'mocked_key',
                        'client_secret' => 'mocked_secret',
                    ),
                    array(),
                    array(),
                ),
                new \InvalidArgumentException('Any grant_type given.'),
            ),
            'bad_grant_type' => array(
                array(
                    array(
                        'grant_type' => 'bad_grant_type',
                        'client_api_key' => 'mocked_key',
                        'client_secret' => 'mocked_secret',
                    ),
                    array(),
                    array(),
                ),
                new \InvalidArgumentException('Given grant_type is invalid.'),
            ),
            'any_application' => array(
                array(
                    array(
                        'grant_type' => 'mocked_grant_type',
                        'client_api_key' => 'bad_api_key',
                        'client_secret' => 'bad_secret',
                    ),
                    array(),
                    array(),
                ),
                new InvalidGrantException(
                    new LoginAttempt(array(), array(), array()),
                    'Any application found for given api_key / secret.'
                ),
            ),
        );
    }

    /**
     * Tests grant() method error cases.
     *
     * @dataProvider errorCaseProvider
     */
    public function testGrantErrors(array $grantArgs, \Exception $expectedException)
    {
        $applicationLoader = $this->prophesize(ApplicationLoaderInterface::class);
        $applicationLoader
            ->retrieveByApiKeyAndSecret(Argument::type('string'), Argument::type('string'))
            ->will(function ($args) {
                return $args[0] == 'bad_api_key' && $args[1] == 'bad_secret' ?
                    null :
                    new Application()
                ;
            })
        ;
        $randomTokenGenerator = $this->prophesize(RandomTokenGenerator::class);
        $randomTokenGenerator->generate('access_token')
            ->willReturn('mocked_access_token_hash')
        ;

        $server = new Server(
            $this->prophesize(EventDispatcherInterface::class)->reveal(),
            $applicationLoader->reveal(),
            $randomTokenGenerator->reveal(),
            array(),
            array('mocked_grant_type' => $this->prophesize(GrantExtensionInterface::class)->reveal())
        );

        $this->expectException(get_class($expectedException));
        $this->expectExceptionMessage($expectedException->getMessage());

        $server->grant(...$grantArgs);
    }
}

class MockedAccessToken extends AccessToken
{
}
class MockedRefreshToken extends RefreshToken
{
}

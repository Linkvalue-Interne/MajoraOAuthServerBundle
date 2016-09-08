<?php
namespace Majora\Component\OAuth\Tests\Entity;

use Majora\Component\OAuth\Entity\Token;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * Unit tests class for Majora\Component\OAuth\Entity\Token
 */
class TokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Token
     */
    protected $token;

    /**
     * @var AccountInterface
     */
    protected $account;

    /**
     * @var ApplicationInterface
     */
    protected $application;

    public static function setUpBeforeClass()
    {
        ClockMock::register(MockedToken::class);
    }

    protected function setUp()
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

        $this->token = new MockedToken(
            $this->application = $application->reveal(),
            $this->account = $account->reveal(),
            42
        );
    }


    /**
     * Roles testing provider
     */
    public function rolesProvider()
    {
        return [
            [
                ['ROLE_1', 'ROLE_3'],
                ['ROLE_1', 'ROLE_2', 'ROLE_3'],
                ['ROLE_1', 'ROLE_3']
            ],
            [
                ['ROLE_1', 'ROLE_3'],
                ['ROLE_1', 'ROLE_2'],
                ['ROLE_1']
            ],
            [
                ['ROLE_4', 'ROLE_3'],
                ['ROLE_1', 'ROLE_2'],
                []
            ],
            [
                ['ROLE_1', 'ROLE_2', 'ROLE_3'],
                ['ROLE_1', 'ROLE_2', 'ROLE_3'],
                ['ROLE_1', 'ROLE_2', 'ROLE_3']
            ]
        ];
    }

    /**
     * Test the getRoles() method.
     *
     * @dataProvider rolesProvider
     */
    public function testRoles(array $applicationRoles, array $accountRoles, array $expectedRoles)
    {
        $application = $this->prophesize(ApplicationInterface::class);
        $application
            ->getSecret()
            ->shouldBeCalled();
        $application
            ->getRoles()
            ->willReturn($applicationRoles)
            ->shouldBeCalled();

        $account = $this->prophesize(AccountInterface::class);
        $account
            ->getPassword()
            ->shouldBeCalled();
        $account
            ->getRoles()
            ->willReturn($accountRoles)
            ->shouldBeCalled();

        $token = new MockedToken(
            $application->reveal(),
            $account->reveal(),
            42
        );

        $this->assertEquals($expectedRoles, $token->getRoles(), '', 0.0, 10, true);
    }

    /**
     * Test the expiration datetime is the datetime when the token expires in.
     */
    public function testExpireAt()
    {
        ClockMock::withClockMock(true);

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

        $this->token = new MockedToken(
            $application->reveal(),
            $account->reveal(),
            42
        );

        $this->assertEquals(\DateTime::createFromFormat('U', time() + intval(42)), $this->token->getExpireAt());
    }

    /**
     * Test if the expiration datetime given in constructor is equals to returned by the getter.
     */
    public function testConstructorExpireAt()
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

        $this->token = new MockedToken(
            $application->reveal(),
            $account->reveal(),
            42,
            $expectedExpireAt = \DateTime::createFromFormat('U', time() + intval(42))
        );

        $this->assertEquals($expectedExpireAt, $this->token->getExpireAt());
    }

    /**
     * Test if the account given in constructor is equals to returned by the getter.
     */
    public function testConstructorAccount()
    {
        $this->assertEquals($this->account, $this->token->getAccount());
    }

    /**
     * Test if the application given in constructor is equals to returned by the getter.
     */
    public function testConstructorApplication()
    {
        $this->assertEquals($this->application, $this->token->getApplication());
    }

    /**
     * Test if the expiration seconds given in constructor is equals to returned by the getter.
     */
    public function testConstructorExpireIn()
    {
        $this->assertEquals(42, $this->token->getExpireIn());
    }

    /**
     * Test if the hash given in constructor is equals to returned by the getter.
     */
    public function testConstructorHash()
    {
        $application = $this->prophesize(ApplicationInterface::class);
        $account = $this->prophesize(AccountInterface::class);

        $this->token = new MockedToken(
            $application->reveal(),
            $account->reveal(),
            42,
            null,
            'mocked_hash'
        );

        $this->assertEquals('mocked_hash', $this->token->getHash());
        $this->assertEquals('mocked_hash', (string)$this->token);
    }
}

class MockedToken extends Token
{
}

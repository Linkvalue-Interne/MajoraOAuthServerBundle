<?php
namespace Majora\Component\OAuth\Tests\Entity;

use Majora\Component\OAuth\Entity\Account;

/**
 * Class AccountTest
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class AccountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Account
     */
    protected $account;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->account = new Account();
    }

    /**
     * Test getScopes() method.
     */
    public function testScopes()
    {
        $expectedScopes = [
            'id' => 'id',
            'default' => ['id', 'owner_id', 'username'],
        ];
        $this->assertEquals($expectedScopes, $this->account->getScopes(), '', 0.0, 10, true);
    }

    /**
     * Test the ownerId property.
     */
    public function testOwnerId()
    {
        $self = $this->account->setOwnerId(42);
        $this->assertSame($this->account, $self);
        $this->assertEquals(42, $this->account->getOwnerId());
    }

    /**
     * Test the username property.
     */
    public function testUsername()
    {
        $self = $this->account->setUsername('mocked_username');
        $this->assertSame($this->account, $self);
        $this->assertEquals('mocked_username', $this->account->getUsername());
    }

    /**
     * Test the password property.
     */
    public function testPassword()
    {
        $self = $this->account->setPassword('mocked_password');
        $this->assertSame($this->account, $self);
        $this->assertEquals('mocked_password', $this->account->getPassword());
    }

    public function testSalt()
    {
        $this->assertNotEmpty($this->account->getSalt());
    }

    public function testApplications()
    {
        $self = $this->account->setApplications([]);
        $this->assertSame($this->account, $self);
        $this->assertEquals([], $this->account->getApplications());
    }
}

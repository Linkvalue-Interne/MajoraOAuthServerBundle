<?php
namespace Majora\Component\OAuth\Tests\Entity;

use Majora\Component\OAuth\Entity\Application;

/**
 * Class ApplicationTest
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->application = new Application();
    }

    /**
     * Test getScopes() method.
     */
    public function testScopes()
    {
        $expectedScopes = [
            'id' => 'id',
            'default' => ['id', 'domain', 'allowed_scopes'],
        ];
        $this->assertEquals($expectedScopes, $this->application->getScopes(), '', 0.0, 10, true);
    }

    /**
     * Test the apiKey property.
     */
    public function testApiKey()
    {
        $self = $this->application->setApiKey('mocked_apikey');
        $this->assertSame($this->application, $self);
        $this->assertEquals('mocked_apikey', $this->application->getApiKey());
    }

    /**
     * Test the secret property.
     */
    public function testSecret()
    {
        $self = $this->application->setSecret('mocked_secret');
        $this->assertSame($this->application, $self);
        $this->assertEquals('mocked_secret', $this->application->getSecret());
    }

    /**
     * Test the domain property.
     */
    public function testDomain()
    {
        $self = $this->application->setDomain('mocked_domain');
        $this->assertSame($this->application, $self);
        $this->assertEquals('mocked_domain', $this->application->getDomain());
    }

    /**
     * Test the allowedScopes property.
     */
    public function testAllowedScopes()
    {
        $self = $this->application->setAllowedScopes([]);
        $this->assertSame($this->application, $self);
        $this->assertEquals([], $this->application->getAllowedScopes());
    }

    /**
     * Test the allowedGrantTypes property.
     */
    public function testAllowedGrantTypes()
    {
        $self = $this->application->setAllowedGrantTypes([]);
        $this->assertSame($this->application, $self);
        $this->assertEquals([], $this->application->getAllowedGrantTypes());
    }

    /**
     * Test roles property.
     */
    public function testRoles()
    {
        $self = $this->application->setRoles([]);
        $this->assertSame($this->application, $self);
        $this->assertEquals([], $this->application->getRoles());
    }

    /**
     * Test accounts property.
     */
    public function testAccounts()
    {
        $self = $this->application->setAccounts([]);
        $this->assertSame($this->application, $self);
        $this->assertEquals([], $this->application->getAccounts());
    }
}

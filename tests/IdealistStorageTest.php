<?php

use app\oauth2\libs\IdealistStorage;

class IdealistStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetClientDetails()
    {
        $this->markTestIncomplete();
    }

    public function testGetClientScope()
    {
        $this->markTestIncomplete();
    }

    public function testCheckRestrictedGrantType()
    {
        $this->markTestIncomplete();
    }

    public function testGetPublicKey()
    {
        $this->markTestIncomplete();
    }

    public function testGetPrivateKey()
    {
        $this->markTestIncomplete();
    }

    public function testGetEncryptionAlgorithm()
    {
        $storage = new IdealistStorage();
        $this->assertEquals('RS256', $storage->getEncryptionAlgorithm());
    }

    public function testCheckUserCredentials()
    {
        $this->markTestIncomplete();
    }

    public function testGetUserDetails()
    {
        $this->markTestIncomplete();
    }

    public function testGetUserClaims()
    {
        $this->markTestIncomplete();
    }

    public function testGetAccessToken()
    {
        $this->markTestIncomplete();
    }

    public function testSetAccessToken()
    {
        $this->markTestIncomplete();
    }
}

<?php

use PeterColes\Betfair\Betfair;

require '.env.php'; // load authentication credentials

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->appKey = getenv('APP_KEY');
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf('PeterColes\Betfair\Api\Auth\Auth', Betfair::auth());
    }

    public function testLogin()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        $this->assertTrue(is_string($token));
        $this->assertEquals(44, strlen($token));
    }

    public function testLogout()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        Betfair::auth()->logout($this->appKey, $token);

        // // Test simply confirms that logout didn't fail.
        $this->addToAssertionCount(1);
    }

    public function testNoSessionAfterLogout()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        Betfair::auth()->logout($this->appKey, $token);

        // First logout is fine, but the second should throw a NO_SESSION exception.
        // We can't yet tell of that is the exception being thown and
        // need more extensive exception handling (planned) to be sure.
        $this->setExpectedException('Exception');
        Betfair::auth()->logout($this->appKey, $token);
    }
}

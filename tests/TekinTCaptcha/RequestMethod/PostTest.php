<?php

namespace TekinTCaptcha\RequestMethod;

use TekinTCaptcha\RequestParameters;
use \PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public static $assert = null;
    protected $parameters = null;
    protected $runcount = 0;

    public function setUp()
    {
        $this->parameters = new RequestParameters("aid","AppSecretKey", "Ticket","Randstr","UserIP");
    }

    public function tearDown()
    {
        self::$assert = null;
    }

    public function testHTTPContextOptions()
    {
        $req = new Post();
        self::$assert = array($this, "httpContextOptionsCallback");
        $req->submit($this->parameters);
        $this->assertEquals(1, $this->runcount, "The assertion was ran");
    }

    public function testSSLContextOptions()
    {
        $req = new Post();
        self::$assert = array($this, "sslContextOptionsCallback");
        $req->submit($this->parameters);
        $this->assertEquals(1, $this->runcount, "The assertion was ran");
    }

    public function httpContextOptionsCallback(array $args)
    {
        $this->runcount++;
        $this->assertCommonOptions($args);

        $options = stream_context_get_options($args[2]);
        $this->assertArrayHasKey('http', $options);

        $this->assertArrayHasKey('method', $options['http']);
        $this->assertEquals("POST", $options['http']['method']);

        $this->assertArrayHasKey('content', $options['http']);
        $this->assertEquals($this->parameters->toQueryString(), $options['http']['content']);

        $this->assertArrayHasKey('header', $options['http']);
        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
        );
        foreach ($headers as $header) {
            $this->assertContains($header, $options['http']['header']);
        }
    }

    public function sslContextOptionsCallback(array $args)
    {
        $this->runcount++;
        $this->assertCommonOptions($args);

        $options = stream_context_get_options($args[2]);
        $this->assertArrayHasKey('http', $options);
        $this->assertArrayHasKey('verify_peer', $options['http']);
        $this->assertTrue($options['http']['verify_peer']);

        $key = version_compare(PHP_VERSION, "5.6.0", "<") ? "CN_name" : "peer_name";

        $this->assertArrayHasKey($key, $options['http']);
        $this->assertEquals("www.yunnan.ws", $options['http'][$key]);
    }

    protected function assertCommonOptions(array $args)
    {
        $this->assertCount(3, $args);
        $this->assertStringStartsWith("https://ssl.captcha.qq.com/ticket/verify", $args[0]);
        $this->assertFalse($args[1]);
        $this->assertTrue(is_resource($args[2]), "The context options should be a resource");
    }
}

function file_get_contents()
{
    if (PostTest::$assert) {
        return call_user_func(PostTest::$assert, func_get_args());
    }
    // Since we can't represent maxlen in userland...
    return call_user_func_array('file_get_contents', func_get_args());
}

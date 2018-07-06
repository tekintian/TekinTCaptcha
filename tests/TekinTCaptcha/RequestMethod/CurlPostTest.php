<?php

namespace TekinTCaptcha\RequestMethod;

use \TekinTCaptcha\RequestParameters;
use \PHPUnit\Framework\TestCase;

class CurlPostTest extends TestCase
{

    protected function setUp()
    {
        if (!extension_loaded('curl')) {
            $this->markTestSkipped(
                    'The cURL extension is not available.'
            );
        }
    }

    public function testSubmit()
    {
        $curl = $this->getMock('\\TekinTCaptcha\\RequestMethod\\Curl',
                array('init', 'setoptArray', 'exec', 'close'));
        $curl->expects($this->once())
                ->method('init')
                ->willReturn(new \stdClass);
        $curl->expects($this->once())
                ->method('setoptArray')
                ->willReturn(true);
        $curl->expects($this->once())
                ->method('exec')
                ->willReturn('RESPONSEBODY');
        $curl->expects($this->once())
                ->method('close');

        $pc = new CurlPost($curl);
        $response = $pc->submit(new RequestParameters("aid","AppSecretKey", "Ticket","Randstr","UserIP"));
        $this->assertEquals('RESPONSEBODY', $response);
    }
}

<?php

namespace TekinTCaptcha;
use \PHPUnit\Framework\TestCase;

class TencentCaptchaTest extends TestCase
{
    /**
     * @expectedException \RuntimeException
     * @dataProvider invalidAppSecretKeyProvider
     */
    public function testExceptionThrownOnInvalidAppSecretKey($invalid)
    {
        $rc = new TekinTCaptcha($invalid);
    }

    public function invalidAppSecretKeyProvider()
    {
        return array(
            array(''),
            array(null),
            array(0),
            array(new \stdClass()),
            array(array()),
        );
    }

    public function testVerifyReturnsErrorOnMissingTicket()
    {
        $rc = new TekinTCaptcha('Ticket','Randstr');
        $Ticket = $rc->verify('');
        $this->assertFalse($Ticket->isSuccess());
        $this->assertEquals(array('missing-input-ticket'), $Ticket->getErrMsg());
    }

    public function testVerifyReturnsErrorOnMissingRandstr()
    {
        $rc = new TekinTCaptcha('Ticket','Randstr');
        $Ticket = $rc->verify('Ticket');
        $this->assertFalse($Ticket->isSuccess());
        $this->assertEquals(array('missing-input-randstr'), $Ticket->getErrMsg());
    }
    public function testVerifyReturnsResponse()
    {
        $method = $this->getMock('\\TekinTCaptcha\\RequestMethod', array('submit'));
        $method->expects($this->once())
                ->method('submit')
                ->with($this->callback(function ($params) {

                            return true;
                        }))
                ->will($this->returnValue('{"success": true}'));
        ;
        $rc = new TekinTCaptcha('aid','AppSecretKey', $method);
        $Ticket = $rc->verify('Ticket','Randstr');
        $this->assertTrue($Ticket->isSuccess());
    }
}

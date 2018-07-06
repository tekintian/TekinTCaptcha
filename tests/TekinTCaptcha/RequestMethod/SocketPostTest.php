<?php

namespace TekinTCaptcha\RequestMethod;

use TekinTCaptcha\RequestParameters;
use \PHPUnit\Framework\TestCase;

class SocketPostTest extends TestCase
{

    public function testSubmitSuccess()
    {
        $socket = $this->getMock('\\TekinTCaptcha\\RequestMethod\\Socket', array('fsockopen', 'fwrite', 'fgets', 'feof', 'fclose'));
        $socket->expects($this->once())
                ->method('fsockopen')
                ->willReturn(true);
        $socket->expects($this->once())
                ->method('fwrite');
        $socket->expects($this->once())
                ->method('fgets')
                ->willReturn("HTTP/1.1 200 OK\n\nRESPONSEBODY");
        $socket->expects($this->exactly(2))
                ->method('feof')
                ->will($this->onConsecutiveCalls(false, true));
        $socket->expects($this->once())
                ->method('fclose')
                ->willReturn(true);

        $ps = new SocketPost($socket);
        $response = $ps->submit(new RequestParameters("aid","AppSecretKey", "Ticket","Randstr","UserIP"));
        $this->assertEquals('RESPONSEBODY', $response);
    }

    public function testSubmitBadResponse()
    {
        $socket = $this->getMock('\\TekinTCaptcha\\RequestMethod\\Socket', array('fsockopen', 'fwrite', 'fgets', 'feof', 'fclose'));
        $socket->expects($this->once())
                ->method('fsockopen')
                ->willReturn(true);
        $socket->expects($this->once())
                ->method('fwrite');
        $socket->expects($this->once())
                ->method('fgets')
                ->willReturn("HTTP/1.1 500 NOPEn\\nBOBBINS");
        $socket->expects($this->exactly(2))
                ->method('feof')
                ->will($this->onConsecutiveCalls(false, true));
        $socket->expects($this->once())
                ->method('fclose')
                ->willReturn(true);

        $ps = new SocketPost($socket);
        $response = $ps->submit(new RequestParameters("aid","AppSecretKey", "Ticket","Randstr","UserIP"));
        $this->assertEquals(SocketPost::BAD_RESPONSE, $response);
    }

    public function testSubmitBadRequest()
    {
        $socket = $this->getMock('\\TekinTCaptcha\\RequestMethod\\Socket', array('fsockopen'));
        $socket->expects($this->once())
                ->method('fsockopen')
                ->willReturn(false);
        $ps = new SocketPost($socket);
        $response = $ps->submit(new RequestParameters("aid","AppSecretKey", "Ticket","Randstr","UserIP"));
        $this->assertEquals(SocketPost::BAD_REQUEST, $response);
    }
}

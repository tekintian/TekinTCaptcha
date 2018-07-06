<?php

namespace TekinTCaptcha;
use \PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{

    /**
     * @dataProvider provideJson
     */ 
    public function testFromJson($json, $success, $msg, $status, $evil_level)
    {
        $Ticket = Response::fromJson($json);
        $this->assertEquals($success, $Ticket->isSuccess());
        $this->assertEquals($msg, $Ticket->getErrMsg());
        $this->assertEquals($status, $Ticket->getStatus());
        $this->assertEquals($evil_level, $Ticket->getEvilLevel());
    }

    public function provideJson()
    {
        return array(
            array('{"success": true}', true, array(), 1,null),
            array('{"success": true, "err_msg": "OK"}', true, array(), 1,null),
            array('{"success": false, "err_msg": ["test"]}', false, array('test'), 0, null),
            array('{"success": false, "err_msg": ["test"], "response": "0"}', false, array('test'), 0, null),
            array('{"success": true, "err_msg": ["test"]}', true, array(),1, null),
            array('{"success": true, "err_msg": ["OK"], "response": "1"}', true, array(), '1',10),
            array('{"success": false}', false, array(), 0, null),
          
            array('BAD JSON', false, array('invalid-json'), -1, null),
        );
    }

    public function testIsSuccess()
    {
        $Ticket = new Response(true);
        $this->assertTrue($Ticket->isSuccess());

        $Ticket = new Response(false);
        $this->assertFalse($Ticket->isSuccess());

        $Ticket = new Response(true, array(), 1);
        $this->assertEquals('1', $Ticket->getStatus());
    }

    public function testGetErrMsg()
    {
        $errMsg = array('test');
        $Ticket = new Response(true, $errMsg);
        $this->assertEquals($errMsg, $Ticket->getErrMsg());
    }

    public function testGetStatus()
    {
      $status = 1;
      $errMsg = array();
      $Ticket = new Response(true, $errMsg, $status);
      $this->assertEquals($status, $Ticket->getStatus());
    }

     public function testGetEvilLevel()
    {
      $evilLevel = 10;
      $errMsg = array();
      $Ticket = new Response(true, $errMsg, 1, $evilLevel);
      $this->assertEquals($evilLevel, $Ticket->getEvilLevel());
    }
}

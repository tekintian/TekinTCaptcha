<?php

namespace TekinTCaptcha;
use \PHPUnit\Framework\TestCase;

class RequestParametersTest extends TestCase
{

    public function provideValidData()
    {
        return array(
            array('aid','AppSecretKey', 'Ticket','Randstr','UserIP',array(
                'aid'=>'aid','AppSecretKey'=>'AppSecretKey','Ticket'=>'Ticket','Randstr'=>'Randstr','UserIP'=>'UserIP'
            ),array('aid','AppSecretKey', 'Ticket','Randstr','UserIP',array('aid'=>'aid','AppSecretKey'=>'AppSecretKey','Ticket'=>'Ticket','Randstr'=>'Randstr'),'aid=aid&AppSecretKey=AppSecretKey&Ticket=Ticket&Randstr=Randstr')
        ));
    }

    /**
     * @dataProvider provideValidData
     */
    public function testToArray($aid, $AppSecretKey, $Ticket, $Randstr,$UserIP, $expectedArray, $expectedQuery)
    {
        $params = new RequestParameters($aid, $AppSecretKey, $Ticket, $Randstr,$UserIP);
        $this->assertEquals($params->toArray(), $expectedArray);
    }

    /**
     * @dataProvider provideValidData
     */
    public function testToQueryString($aid, $AppSecretKey, $Ticket, $Randstr,$UserIP, $expectedArray, $expectedQuery)
    {
        $params = new RequestParameters($aid, $AppSecretKey, $Ticket, $Randstr,$UserIP);
        $this->assertEquals($params->toQueryString(), $expectedQuery);
        
    }
}

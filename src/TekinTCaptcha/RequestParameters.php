<?php

namespace TekinTCaptcha;

/**
 * Stores and formats the parameters for the request to the TekinTCaptcha service.
 */
class RequestParameters
{
    /**
    * app id
     * @var int
    */
    private $aid;

    /**
     * Site AppSecretKey.
     * @var string
     */
    private $AppSecretKey;

    /**
     * Form Ticket.
     * @var string
     */
    private $Ticket;

     /**
     * Form Randstr.
     * @var string
     */
    private $Randstr;

    /**
     * Remote user's IP address.
     * @var string
     */
    private $UserIP;

    /**
     * 
     *
     * @param int $aid Site app id.
     * @param string $AppSecretKey Site AppSecretKey.
     * @param string $Ticket Value from TekinTCaptcha response form field.
     * @param string $Randstr Value from TekinTCaptcha response form field.
     * @param string $UserIP User's IP address.
     */
    public function __construct($aid, $AppSecretKey, $Ticket, $Randstr, $UserIP = null) {
        $this->aid = $aid;
        $this->AppSecretKey = $AppSecretKey;
        $this->Ticket = $Ticket;
        $this->Randstr = $Randstr;
        if (is_null($UserIP)) {
            $this->UserIP = $this->getRealIp();
        }else{
           $this->UserIP = $UserIP;
        }
        
    }

    /**
     * Array representation.
     *
     * @return array Array formatted parameters.
     */
    public function toArray()
    {

        $params = array('aid' => $this->aid, 'AppSecretKey' => $this->AppSecretKey, 'Ticket' => $this->Ticket, 'Randstr' => $this->Randstr, 'UserIP'=>$this->UserIP);

        return $params;
    }

    /**
     * Query string representation for HTTP request.
     *
     * @return string Query string formatted parameters.
     */
    public function toQueryString()
    {
        return http_build_query($this->toArray());
        // return http_build_query($this->toArray(), '', '&');
    }

    /**
     * Gets the real ip.
     * @author     (tekin <tekintian@gmail.com>)
     * @return     boolean  The real ip.
     */
    public function getRealIp(){
        $ip = false;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

}

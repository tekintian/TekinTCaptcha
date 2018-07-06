<?php

namespace TekinTCaptcha\RequestMethod;

use TekinTCaptcha\RequestMethod;
use TekinTCaptcha\RequestParameters;

/**
 * Sends a POST request to the TekinTCaptcha service, but makes use of fsockopen()
 * instead of get_file_contents(). This is to account for people who may be on
 * servers where allow_url_open is disabled.
 */
class SocketPost implements RequestMethod
{
    /**
     * TekinTCaptcha service host.
     * @const string
     */
    const RECAPTCHA_HOST = 'ssl.captcha.qq.com';

    /**
     * @const string TekinTCaptcha service path
     */
    const SITE_VERIFY_PATH = '/ticket/verify';

    /**
     * @const string Bad request error
     */
    const BAD_REQUEST = '{"success": false, "err_msg": ["invalid-request"]}';

    /**
     * @const string Bad response error
     */
    const BAD_RESPONSE = '{"success": false, "err_msg": ["invalid-response"]}';

    /**
     * Socket to the TekinTCaptcha service
     * @var Socket
     */
    private $socket;

    /**
     * Constructor
     *
     * @param \TekinTCaptcha\RequestMethod\Socket $socket optional socket, injectable for testing
     */
    public function __construct(Socket $socket = null)
    {
        if (!is_null($socket)) {
            $this->socket = $socket;
        } else {
            $this->socket = new Socket();
        }
    }

    /**
     * Submit the POST request with the specified parameters.
     *
     * @param RequestParameters $params Request parameters
     * @return string Body of the TekinTCaptcha response
     */
    public function submit(RequestParameters $params)
    {
        $errno = 0;
        $errstr = '';

        if (false === $this->socket->fsockopen('ssl://' . self::RECAPTCHA_HOST, 443, $errno, $errstr, 30)) {
            return self::BAD_REQUEST;
        }

        $content = $params->toQueryString();

        $request = "POST " . self::SITE_VERIFY_PATH . " HTTP/1.1\r\n";
        $request .= "Host: " . self::RECAPTCHA_HOST . "\r\n";
        $request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $request .= "Content-length: " . strlen($content) . "\r\n";
        $request .= "Connection: close\r\n\r\n";
        $request .= $content . "\r\n\r\n";

        $this->socket->fwrite($request);
        $rawResponse = '';

        while (!$this->socket->feof()) {
            $rawResponse .= $this->socket->fgets(4096);
        }

        $this->socket->fclose();

        if (0 !== strpos($rawResponse, 'HTTP/1.1 200 OK')) {
            return self::BAD_RESPONSE;
        }

        $parts = preg_split("#\n\s*\n#Uis", $rawResponse);

        return $parts[1];
    }
}

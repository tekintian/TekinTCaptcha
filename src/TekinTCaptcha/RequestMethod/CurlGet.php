<?php

namespace TekinTCaptcha\RequestMethod;

use TekinTCaptcha\RequestMethod;
use TekinTCaptcha\RequestParameters;

/**
 * Sends cURL request to the TekinTCaptcha service.
 * Note: this requires the cURL extension to be enabled in PHP
 * @see http://php.net/manual/en/book.curl.php
 */
class CurlGet implements RequestMethod
{
    /**
     * URL to which requests are sent via cURL.
     * @const string
     */
    const TCAPTCHA_VERIFY_URL = 'https://ssl.captcha.qq.com/ticket/verify';

    /**
     * Curl connection to the TekinTCaptcha service
     * @var Curl
     */
    private $curl;

    public function __construct(Curl $curl = null)
    {
        if (!is_null($curl)) {
            $this->curl = $curl;
        } else {
            $this->curl = new Curl();
        }
    }

    /**
     * Submit the cURL request with the specified parameters.
     *
     * @param RequestParameters $params Request parameters
     * @return string Body of the TekinTCaptcha response
     */
    public function submit(RequestParameters $params)
    {
        $handle = $this->curl->init();

        $options = array(
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPGET => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22',
            CURLOPT_URL => self::TCAPTCHA_VERIFY_URL.'?'.$params->toQueryString(),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        );
        $this->curl->setoptArray($handle, $options);

        $rawResponse = $this->curl->exec($handle);
        $this->curl->close($handle);
 
        return $rawResponse;
    }
}

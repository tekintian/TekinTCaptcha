<?php

namespace TekinTCaptcha\RequestMethod;

use TekinTCaptcha\RequestMethod;
use TekinTCaptcha\RequestParameters;

/**
 * Sends cURL request to the TekinTCaptcha service.
 * Note: this requires the cURL extension to be enabled in PHP
 * @see http://php.net/manual/en/book.curl.php
 */
class CurlPost implements RequestMethod
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
        $handle = $this->curl->init(self::TCAPTCHA_VERIFY_URL);

        $options = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params->toQueryString(),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLINFO_HEADER_OUT => false,
            CURLOPT_HEADER => false,
            CURLOPT_DNS_USE_GLOBAL_CACHE => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        );
        $this->curl->setoptArray($handle, $options);

        $rawResponse = $this->curl->exec($handle);
        $this->curl->close($handle);

        return $rawResponse;
    }
}

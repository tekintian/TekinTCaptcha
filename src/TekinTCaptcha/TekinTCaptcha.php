<?php

namespace TekinTCaptcha;

/**
 * TekinTCaptcha client.
 */
class TekinTCaptcha
{
     /**
     * Shared app id for the site.
     * @var string
     */
    private $aid;

    /**
     * Shared AppSecretKey for the site.
     * @var string
     */
    private $AppSecretKey;

    /**
     * Method used to communicate with service. Defaults to POST request.
     * @var RequestMethod
     */
    private $requestMethod;

    /**
     * Create a configured instance to use the TekinTCaptcha service.
     *
     * @param int $aid shared App ID between site and TCaptcha server.
     * @param string $AppSecretKey shared AppSecretKey between site and  TCaptcha server.
     * @param RequestMethod $requestMethod method used to send the request. Defaults to POST.
     * @throws \RuntimeException if $aid, $AppSecretKey is invalid
     */
    public function __construct($aid, $AppSecretKey, RequestMethod $requestMethod = null)
    {
       
        if (empty($aid)) {
            throw new \RuntimeException('No app id provided');
        }
        if (!is_numeric($aid)) {
            throw new \RuntimeException("The provided aid must be a number");
        }

        if (empty($AppSecretKey)) {
            throw new \RuntimeException('No AppSecretKey provided');
        }

        if (!is_string($AppSecretKey)) {
            throw new \RuntimeException('The provided AppSecretKey must be a string');
        }

        $this->aid = $aid;
        $this->AppSecretKey = $AppSecretKey;

        if (!is_null($requestMethod)) {
            $this->requestMethod = $requestMethod;
        } else {
            $this->requestMethod = new RequestMethod\CurlGet();
        }
    }

    /**
     * Calls the TekinTCaptcha siteverify API to verify whether the user passes
     * @param string $Ticket The value of Ticket in the submitted form.
     * @param string $Randstr The value of Randstr in the submitted form.
     * @param string $UserIP The end user's IP address.
     * @return Response Response from the service.
     */
    public function verify($Ticket, $Randstr, $UserIP = null)
    {
        // Discard empty solution submissions
        if (empty($Ticket)) {
            $recaptchaResponse = new Response(false, array('missing-input-ticket'));
            return $recaptchaResponse;
        }
        if (empty($Randstr)) {
            $recaptchaResponse = new Response(false, array('missing-input-randstr'));
            return $recaptchaResponse;
        }

        $params = new RequestParameters($this->aid, $this->AppSecretKey, $Ticket, $Randstr, $UserIP);

        $rawResponse = $this->requestMethod->submit($params);

        return Response::fromJson($rawResponse);
    }
}

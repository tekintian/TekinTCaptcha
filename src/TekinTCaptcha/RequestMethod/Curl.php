<?php

namespace TekinTCaptcha\RequestMethod;

/**
 * Convenience wrapper around the cURL functions to allow mocking.
 */
class Curl
{

    /**
     * @see http://php.net/curl_init
     * @param string $url
     * @return resource cURL handle
     */
    public function init($url = null)
    {
        return curl_init($url);
    }

    /**
     * @see http://php.net/curl_setopt_array
     * @param resource $ch
     * @param array $options
     * @return bool
     */
    public function setoptArray($ch, array $options)
    {
        return curl_setopt_array($ch, $options);
    }

    /**
     * @see http://php.net/curl_exec
     * @param resource $ch
     * @return mixed
     */
    public function exec($ch)
    {
        return curl_exec($ch);
    }

    /**
     * @see http://php.net/curl_close
     * @param resource $ch
     */
    public function close($ch)
    {
        curl_close($ch);
    }
}

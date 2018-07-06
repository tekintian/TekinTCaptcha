<?php

namespace TekinTCaptcha;

/**
 * The response returned from the service.
 */
class Response
{
    /**
     * Success or failure.
     * @var boolean
     */
    private $success = false;

    /**
     * 验证信息[optional]
     * @var array()
     */
    private $msg = array();

    /**
     * 状态码  1 验证成功； 0 验证失败； 100 AppSecretKey参数校验错误； -1 通讯失败
     * @var int
     */
    private $status = -1;

    /**
     * 恶意等级[optional]  0--100 ；  -1 通讯失败
     * @var int
     */
    private $evil_level = null;

    /**
     * Build the response from the expected JSON returned by the service.
     *
     * @param string $json
     * @return \TekinTCaptcha\Response
     */
    public static function fromJson($json)
    {
        $responseData = json_decode($json, true);

        if (!$responseData) {
            return new Response(false, array('invalid-json'));
        }

        $status = isset($responseData['response']) ? $responseData['response'] : -1;
        $msg = isset($responseData['err_msg']) ? $responseData['err_msg'] : array();
        $evil_level = isset($responseData['evil_level']) ? $responseData['evil_level'] : null;

        switch ($status) {
            case '0':
            //验证失败
                return new Response(false, $msg, $status, $evil_level);
                break;
            case '1':
            //验证成功
                return new Response(true, $msg, $status, $evil_level);
                break;
             case '100':
              //AppSecretKey参数校验错误
                return new Response(false, $msg, $status, $evil_level);
                break;
            default:
                return new Response(false, $msg, $status, $evil_level);
                break;
        }

    }

    /**
     * Constructor.
     *
     * @param boolean $success
     * @param array $msg
     * @param string $evil_level
     */
    public function __construct($success, $msg = array(), $status = -1, $evil_level = null)
    {
        $this->success = $success;
        $this->msg = $msg;
        $this->status = $status;
        $this->evil_level = $evil_level;
    }

    /**
     * Is success?
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * Get error codes.
     *
     * @return array
     */
    public function getErrMsg()
    {
        return $this->msg;
    }

    /**
     * 状态码获取
     *
     * @return     <type>  The status.
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get evil_level.
     *
     * @return string
     */
    public function getEvilLevel()
    {
      return $this->evil_level;
    }
}

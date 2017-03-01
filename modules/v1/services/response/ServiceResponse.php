<?php

namespace app\modules\v1\services\response;

/**
 * @class ServiceResponse 
 * @brief This Class will handle all response from services
 * @version : 1.0
 */
class ServiceResponse implements ServiceResponseInterface
{

    /**
     * @var boolean status of response
     */
    private $status;

    /**
     * @var int status code of response
     */
    private $statusCode;

    /**
     * @var string message of response
     */
    private $message;

    /**
     * @var string|array of response
     */
    private $response;

    /**
     * @brief Get status of response
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @brief Get status code of response
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @brief Get message of response
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @brief Get response data
     * @return string|array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @brief Setting status of response
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @brief Setting status code of response
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @brief Setting message of response
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @brief Setting data of response
     * @param string|array $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

}

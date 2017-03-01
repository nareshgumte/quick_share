<?php


namespace app\components\utils;

/**
 * class ResponseFormat
 * @brief  This class is used to set the Response status and message for every request
 * @version : 1.0
 */
class ResponseFormat {

    /**
     * @brief set message and status code for response
     * @param object $response
     * @param string $message
     * @param boolean $status
     * @param int|null $statusCode
     * @returns object $response 
     */
    public static function setResponse($response, $message, $status, $statusCode) {

        $response->setStatus($status);
        $response->setStatusCode($statusCode);
        $response->setMessage($message);
        return $response;
    }

}

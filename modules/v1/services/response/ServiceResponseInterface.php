<?php


namespace app\modules\v1\services\response;
/**
 * @interface ResponseServiceInterface
 * @brief Handles the response communication between service and controller
 * @version : 1.0
 */
interface ServiceResponseInterface
{

    public function getStatus();
    public function getStatusCode();
    public function getMessage();
    public function getResponse();
    public function setStatus($status);
    public function setStatusCode($statusCode);
    public function setMessage($message);
    public function setResponse($response);
}

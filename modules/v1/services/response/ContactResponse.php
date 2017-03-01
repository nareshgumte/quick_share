<?php

namespace app\modules\v1\services\response;

/**
 * @class ContactResponse 
 * @brief This Class will handle all response related to user service
 * @version : 1.0
 */
class ContactResponse extends ServiceResponse
{

    /**
     * @brief Setting default response
     */
    public function __construct()
    {
        $this->setStatus(true);
        $this->setStatusCode(200);
        $this->setMessage("Ok");
    }

}

<?php

namespace app\components\authentication;

/**
 * @class RequestValidator
 * @brief validates signature, nonce, auth token based on object created 
 * @version : 1.0
 */
class RequestValidator
{

    /**
     * @var instance of validator class  
     */
    private $_validator;

    /**
     * @brief Setting instance variable with parameters. 
     * @param RequestValidatorInterface $validator
     * @throws \yii\web\HttpException
     * @return boolean 
     */
    public function setValidator(RequestValidatorInterface $validator)
    {
        $this->_validator = $validator;
        //print_r($this->_validator);exit();
    }

    /**
     * @brief will validates signature, nonce, auth token based on object created 
     * @throws \yii\web\HttpException
     */
    public function validateRequest()
    {
        $this->_validator->validate();
    }

}

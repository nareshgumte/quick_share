<?php

namespace app\components\authentication;

/**
 * @interface RequestValidatorInterface
 * @brief  inorder to validate nonce/signature/access token this interface need to be implemented
 * @version : 1.0
 */
interface RequestValidatorInterface
{

    /**
     * @brief validate api request by implementing this interface
     */
    public function validate();
}


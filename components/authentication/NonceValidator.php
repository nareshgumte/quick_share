<?php

namespace app\components\authentication;

use app\components\cache\Caching;

/**
 * @class NonceValidator  
 * @brief Class validates nonce
 * @version : 1.0
 */
class NonceValidator implements RequestValidatorInterface {

    /**
     * @array holds app config params
     */
    protected $configParams;

    /**
     * @var holds signature value
     */
    protected $signature;

    /**
     * @array holds nonce value
     */
    protected $nonce;

    /**
     * @brief Setting instance variable with parameters. 
     * @param array $configParams
     * @param string $signature
     * @param string $nonce
     */
    public function __construct(&$configParams, $signature, $nonce) {
        $this->configParams = $configParams;
        $this->signature = $signature;
        $this->nonce = $nonce;
    }

    /**
     * @brief will validates whether the request is duplicate or not
     * @throws \yii\web\HttpException
     */
    public function validate() {
        if ($this->configParams['preventDuplicates']) {
            $cacheKey = 'nonce-' . $this->nonce . $this->signature;
            if (isset($_COOKIE[$cacheKey])) {
                throw new \yii\web\HttpException(403, 'Duplicate request');
            } else {
                setcookie($cacheKey, 1,  $this->configParams['cacheValueDuration']); // 86400 = 1 day
            }
        }
    }

}

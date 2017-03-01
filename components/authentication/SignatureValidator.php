<?php

namespace app\components\authentication;

use Yii;

/**
 * @class SignatureValidator
 * @brief Validates the requested signature 
 * @version : 1.0
 */
class SignatureValidator implements RequestValidatorInterface {

    /**
     * @array holds app config params
     */
    protected $configParams;

    /**
     * @array holds request attributes for signature calculation
     */
    protected $requestAttributes;

    /**
     * @var holds signature value
     */
    protected $signature;

    /**
     * @array holds nonce value
     */
    protected $nonce;

    /**
     * @array holds auth tokan
     */
    protected $authToken;

    /**
     * @var holds requested actionid
     */
    protected $actionId;

    /**
     * @array holds auth token free actions 
     */
    protected $authFreeActions;

    /**
     * @brief Setting instance variable with parameters. 
     * @param array $configParams application config params
     * @param array $requestAttributes api request params 
     * @param string $signature header signature value
     * @param string $nonce  header nonce value
     * @param string $authToken header access token value
     */
    public function __construct(&$configParams, &$requestAttributes, $signature, $nonce, $authToken, $actionId, $actions) {
        $this->configParams = $configParams;
        $this->requestAttributes = $requestAttributes;
        $this->signature = $signature;
        $this->nonce = $nonce;
        $this->authToken = $authToken;
        $this->actionId = $actionId;
        $this->authFreeActions = (array) $actions;
    }

    /**
     * @brief will validate api signature 
     * @throws \yii\web\HttpException
     * @return boolean 
     */
    public function validate() {
        //print_r($this->authFreeActions);
//        if (!in_array($this->actionId, $this->authFreeActions)) {
            //print_r($this->configParams['useSignature']);exit();
            if ($this->configParams['useSignature']) {
                if (empty($this->signature) ) {
                    //print_r($this->signature);exit();
                    throw new \yii\web\HttpException(403,  'Empty signature key');
                } else {
                    $calculatedSignature = Signature::calculateSignature($this->requestAttributes, $this->authToken,(($this->nonce != '' && $this->nonce != null)?$this->nonce: ''), $this->configParams['publicKey']);
                    //print_r($calculatedSignature);echo"\n";print_r($this->signature);exit();
                    if ($calculatedSignature != $this->signature) {
                        throw new \yii\web\HttpException(403, 'Signature key mismatch');
                    }
                }
            } else {
                Yii::info("Signature validation is set off");
            }
            return true;
//        }
    }

}

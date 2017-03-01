<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\components\request\HttpRequest;
use app\components\authentication\RequestValidator;
use app\components\authentication\SignatureValidator;
use app\components\authentication\NonceValidator;
use app\components\authentication\AuthTokenValidator;
use app\components\cache\Caching;
use app\components\authentication\Cors;

/**
 * @class AuthController
 * @brief This Controller will authorize Api calls (i.e validate nonce, signature, access-token) and validates request parameters
 * @version : 1.0
 */
class AuthController extends ActiveController {

    /**
     * @var controller default model class 
     */
    public $modelClass = '';

    /**
     * @var string requested controller id 
     */
    protected $controllerId;

    /**
     * @var string requested action id 
     */
    protected $actionId;

    /**
     * @var string logged-in userid 
     */
    protected $userId;

    /**
     * @var string signature value set in headers 
     */
    protected $headerSignature;

    /**
     * @var string nonce value set in headers 
     */
    protected $headerNonce;

    /**
     * @var string auth token set in headers 
     */
    protected $authToken;

    /**
     * @array holds access token free actions 
     */
    protected $authFreeActions;

    /**
     * @array holds request params values
     */
    protected $requestAttributes = [];

    /**
     * @var holds request params values
     */
    protected $requestData;

    /**
     * @array holds application configuration params 
     */
    protected $configParams = [];

    /**
     * @array holds invalid request params
     */
    protected $errors = [];

    /**
     * @brief before an action is being triggered get the requested controller id , action id and check data integrity
     * @param $action
     */
    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }
        $this->controllerId = $action->controller->id;
        $this->actionId = $action->id;
        
        $httpRequest = new HttpRequest(Yii::$app->request);

        $this->configParams = &$httpRequest->getAppParams();
        
        $this->headerSignature = $httpRequest->getHeaderValue($this->configParams['signatureHeaderKey']);
        $this->headerNonce = $httpRequest->getHeaderValue($this->configParams['nonceHeaderKey']);
        $this->authToken = $httpRequest->getHeaderValue($this->configParams['accessTokenHeaderKey']);
        
        $httpRequest->processRequestParams($this->configParams['apiRequestKeys']);
        $this->requestAttributes = &$httpRequest->getRequestAttributes();
        $this->requestData = &$httpRequest->getRequestData();
        
        //comment when generate super admin password
        $validator = new RequestValidator();
        
        $validator->setValidator(new AuthTokenValidator($this->authToken, $this->actionId, $this->authFreeActions));
        $validator->validateRequest();
        
        $validator->setValidator(new NonceValidator($this->configParams, $this->headerSignature, $this->headerNonce));
        $validator->validateRequest();

        $validator->setValidator(new SignatureValidator($this->configParams, $this->requestAttributes, $this->headerSignature, $this->headerNonce, $this->authToken, $this->actionId, $this->authFreeActions));
        $validator->validateRequest();
        
        return true;
    }

    /**
     * @brief this function is used to validate api requestParams values  
     * @param array $apiParams
     * @param array $optional
     * @param array $multi
     * @return array 
     */
    public function validateRequestParams($apiParams, $optional = array(), $multi = array()) {
        $hasErrors = false;
        $errors = array();
        foreach ($apiParams as $key => $value) {
            if ($value === false) {
                $hasErrors = true;
                $errors[] = $key . ' is invalid';
            } else if (trim($value) == '' && !in_array($key, $optional)) {
                $hasErrors = true;
                $errors[] = $key . ' is required';
            }
        }

        //Check for multi options
        $multiError = false;
        for ($i = 0, $cnt = count($multi); $i < $cnt; ++$i) {
            $multiError = true;
            $vars = array();
            for ($j = 0, $cnt2 = count($multi[$i]); $j < $cnt2; ++$j) {
                if (empty($apiParams[$multi[$i][$j]])) {
                    $vars[] = $multi[$i][$j];
                } else {
                    $multiError = false;
                }
            }
            if ($multiError) {
                $errors[] = 'Any one of ' . implode(', ', $vars) . ' is required';
            }
            $vars = array();
            $multiError = true;
        }

        $this->errors = $errors;
//        print_r($this->errors);exit();
        return $hasErrors || $multiError;
    }

    /**
     * @brief errors identified in validateRequestParams method will be returned by calling this method    
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
    public function behaviors() {
        
        
        $behaviors = parent::behaviors();

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                // restrict access to
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST'],
                'Access-Control-Request-Headers' => ['x-qs-nonce','x-qs-signature','x-qs-authentication-key'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];
        
        Yii::info( $behaviors, __METHOD__);
        
        return $behaviors;
       /*return [
           'corsFilter' => [
               'class' => CustomCors::className(),
               'cors' => [
                   // restrict access to
                   'Origin' => ['*'],
                   'Access-Control-Request-Method' => ['GET', 'POST'],
                   'Access-Control-Request-Headers' => ['x-nldcs-nonce','x-nldcs-signature','x-nldcs-authentication-key'],
               ],
           ],
       ];*/
   }
}
 

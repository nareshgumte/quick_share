<?php

namespace app\components\authentication;

use app\components\cache\Caching;
use Yii;

/**
 * @class AuthTokenValidator
 * @brief validates authentication token
 * @version : 1.0
 */
class AuthTokenValidator implements RequestValidatorInterface {

    /**
     * @var holds auth token value  
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
     * @param string $authToken
     * @param string $actionId
     * @param array $actions
     */
    public function __construct($authToken, $actionId, $actions = []) {
        $this->authToken = $authToken;
        $this->actionId = $actionId;
        $this->authFreeActions = (array) $actions;
    }

    /**
     * @brief will validates api access token on every api call
     * @throws \yii\web\HttpException
     */
      public function validate() {
        if (!in_array($this->actionId, $this->authFreeActions)) {
            $userDetails = explode("_", $this->authToken);
            //print_r($userDetails);exit();
            if (count($userDetails) == 2) {
                $authKey = Yii::$app->db->createCommand("select access_token from user_login where user_id='$userDetails[1]' order by loggedin_date DESC limit 1")->queryScalar();
                //print($authKey);exit();
                if (empty($this->authToken) || (empty($authKey)) || ($userDetails[0] != $authKey)) {
                    throw new \yii\web\HttpException(412, 'Invalid access token');
                }
            } else {
                throw new \yii\web\HttpException(412, 'Invalid access token');
            }
        }
    }

}

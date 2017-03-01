<?php

namespace app\components\authentication;

use Yii;
use yii\base\ActionFilter;
use yii\web\Request;
use yii\web\Response;

Class Cors extends \yii\filters\Cors {

    //added preflightResponse code to deal with angularjs
    public $preflightResponse = true;

    public function beforeAction($action) {
        $this->request = $this->request ? : Yii::$app->getRequest();
        $this->response = $this->response ? : Yii::$app->getResponse();

        $this->overrideDefaultSettings($action);

        $requestCorsHeaders = $this->extractHeaders();
        $responseCorsHeaders = $this->prepareHeaders($requestCorsHeaders);
        $this->addCorsHeaders($this->response, $responseCorsHeaders);

        //New: If preflightResponse, exit. Needed for proper REST actions with angularjs
        if ($this->preflightResponse && $this->request->method === 'OPTIONS') {
            return false;
        }

        return true;
    }

}

?>
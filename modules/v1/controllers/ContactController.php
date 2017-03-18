<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\v1\controllers;

use Yii;
use app\modules\v1\services\ContactService;

class ContactController extends AuthController {

    /**
     * @inheritdoc
     */
    public function init() {
        $this->authFreeActions = ['add', 'sync-contacts', 'friends', 'visit', 'profile', 'update-status', 'save-files', 'file-list', 'update-profile'];
        parent::init();
    }

    /**
     * @inheritdoc
     * @brief declare http methods for newly written actions 
     * @return array
     */
    public function verbs() {
        $verbs = [
            'add'           =>  ['POST'],
            'sync-contacts' =>  ['POST'],
            'friends'       =>  ['GET'],
            'visit'         =>  ['GET'],
            'profile'       =>  ['GET'],
            'update-status' =>  ['POST'],
            'save-files'    =>  ['POST'],
            'file-list'     =>  ['GET'],
            'update-profile'=>  ['POST']

        ];

        return $verbs;
    }
    
    public function actionAdd() {
        Yii::info('Start of Registration API', __METHOD__);
        $filterRules = [
            'name' => FILTER_FLAG_NONE,
            'phone' => FILTER_FLAG_NONE,
            'facebook_id' => FILTER_FLAG_NONE,
            'designation' => FILTER_FLAG_NONE,
        ];
        $optional = ['facebook_id', 'designation'];
        $apiParams = filter_var_array($this->requestAttributes, $filterRules); //filter here

        if ($this->validateRequestParams($apiParams, $optional)) {
            Yii::info('Invalid Request ' . json_encode($this->getErrors()), __METHOD__);
            throw new \yii\web\HttpException(400, 'Invalid attributes');
        }
//echo "hello";exit;
        $contactService = new ContactService();
        $regResponse = $contactService->saveContact($apiParams);

        if (!$regResponse->getStatus()) {
            throw new \yii\web\HttpException($regResponse->getStatusCode(), $regResponse->getMessage());
        }
        $responseDet = $regResponse->getResponse();        
        return $responseDet;
    }
    public function actionSyncContacts(){
        Yii::info('Sync contacts API', __METHOD__);

        $filterRules = [
            'id' => FILTER_FLAG_NONE,
            'contacts' => FILTER_FLAG_NONE
        ];
        $optional = [];
        $apiParams = filter_var_array($this->requestAttributes, $filterRules); //filter here

        if ($this->validateRequestParams($apiParams, $optional)) {
            Yii::info('Invalid Request ' . json_encode($this->getErrors()), __METHOD__);
            throw new \yii\web\HttpException(400, 'Invalid attributes');
        }

        $contactService = new ContactService();
        $regResponse = $contactService->syncContacts($apiParams);

        if (!$regResponse->getStatus()) {
            throw new \yii\web\HttpException($regResponse->getStatusCode(), $regResponse->getMessage());
        }
        $responseDet = $regResponse->getResponse();
        $response['message'] =  $regResponse->getMessage();
        return $response;
    }
    public function actionFriends(){
        Yii::info('Friends API', __METHOD__);

        $filterRules = [
            'id' => FILTER_FLAG_NONE,
        ];
        $optional = [];
        $apiParams = filter_var_array($this->requestAttributes, $filterRules); //filter here

        if ($this->validateRequestParams($apiParams, $optional)) {
            Yii::info('Invalid Request ' . json_encode($this->getErrors()), __METHOD__);
            throw new \yii\web\HttpException(400, 'Invalid attributes');
        }

        $contactService = new ContactService();
        $responseObj = $contactService->getFriends($apiParams['id']);
        if (!$responseObj->getStatus()) {
            throw new \yii\web\HttpException($responseObj->getStatusCode(), $responseObj->getMessage());
        }
        
        return $responseObj->getResponse();
    }
    public function actionVisit(){
        Yii::info('visit contacts API', __METHOD__);

        $filterRules = [
            'id' => FILTER_FLAG_NONE,
            'friend_id' => FILTER_FLAG_NONE
        ];
        $optional = [];
        $apiParams = filter_var_array($this->requestAttributes, $filterRules); //filter here

        if ($this->validateRequestParams($apiParams, $optional)) {
            Yii::info('Invalid Request ' . json_encode($this->getErrors()), __METHOD__);
            throw new \yii\web\HttpException(400, 'Invalid attributes');
        }

        $contactService = new ContactService();
        $regResponse = $contactService->visitContact($apiParams);

        if (!$regResponse->getStatus()) {
            throw new \yii\web\HttpException($regResponse->getStatusCode(), $regResponse->getMessage());
        }
        $responseDet = $regResponse->getResponse();
        $response['message'] =  $regResponse->getMessage();
        return $response;
    }
    public function actionProfile(){

        Yii::info('visit contacts API', __METHOD__);

        $filterRules = [
            'id' => FILTER_FLAG_NONE,
        ];
        $optional = [];
        $apiParams = filter_var_array($this->requestAttributes, $filterRules); //filter here

        if ($this->validateRequestParams($apiParams, $optional)) {
            Yii::info('Invalid Request ' . json_encode($this->getErrors()), __METHOD__);
            throw new \yii\web\HttpException(400, 'Invalid attributes');
        }
        
        $contactService = new ContactService();
        $regResponse = $contactService->getProfile($apiParams['id']);

        if (!$regResponse->getStatus()) {
            throw new \yii\web\HttpException($regResponse->getStatusCode(), $regResponse->getMessage());
        }
        $responseDet = $regResponse->getResponse();
        $response['message'] =  $regResponse->getMessage();
        $response['response'] = $regResponse->getResponse();
        return $response;
    }
    public function actionUpdateStatus(){
        Yii::info('visit contacts API', __METHOD__);

        $filterRules = [
            'id' => FILTER_FLAG_NONE,
            'status-message' => FILTER_FLAG_NONE
        ];
        
        $optional = [];
        $apiParams = filter_var_array($this->requestAttributes, $filterRules); //filter here

        if ($this->validateRequestParams($apiParams, $optional)) {
            Yii::info('Invalid Request ' . json_encode($this->getErrors()), __METHOD__);
            throw new \yii\web\HttpException(400, 'Invalid attributes');
        }

        $contactService = new ContactService();
        $regResponse = $contactService->updateStatus($apiParams);

        if (!$regResponse->getStatus()) {
            throw new \yii\web\HttpException($regResponse->getStatusCode(), $regResponse->getMessage());
        }
        $responseDet = $regResponse->getResponse();
        $response['message'] =  $regResponse->getMessage();
        return $response;
    }
    public function actionSaveFiles(){
        Yii::info('visit contacts API', __METHOD__);

        $filterRules = [
            'id' => FILTER_FLAG_NONE,
            'files' => FILTER_FLAG_NONE
        ];
        $optional = [];
        $apiParams = filter_var_array($this->requestAttributes, $filterRules); //filter here

        if ($this->validateRequestParams($apiParams, $optional)) {
            Yii::info('Invalid Request ' . json_encode($this->getErrors()), __METHOD__);
            throw new \yii\web\HttpException(400, 'Invalid attributes');
        }

        $contactService = new ContactService();
        $regResponse = $contactService->saveFiles($apiParams);

        if (!$regResponse->getStatus()) {
            throw new \yii\web\HttpException($regResponse->getStatusCode(), $regResponse->getMessage());
        }
        $responseDet = $regResponse->getResponse();
        $response['message'] =  $regResponse->getMessage();
        return $response;
    }
    public function actionFileList(){
        Yii::info('visit contacts API', __METHOD__);

        $filterRules = [
            'id' => FILTER_FLAG_NONE,
        ];
        $optional = [];
        $apiParams = filter_var_array($this->requestAttributes, $filterRules); //filter here

        if ($this->validateRequestParams($apiParams, $optional)) {
            Yii::info('Invalid Request ' . json_encode($this->getErrors()), __METHOD__);
            throw new \yii\web\HttpException(400, 'Invalid attributes');
        }

        $contactService = new ContactService();
        $regResponse = $contactService->getFiles($apiParams['id']);

        if (!$regResponse->getStatus()) {
            throw new \yii\web\HttpException($regResponse->getStatusCode(), $regResponse->getMessage());
        }
        $responseDet = $regResponse->getResponse();
        $response['message'] =  $regResponse->getMessage();
        $response['data'] =  $regResponse->getResponse();
        return $response;
    }
    public function actionUpdateProfile(){
        Yii::info('update profile API', __METHOD__);
        $filterRules = [
            'id' => FILTER_FLAG_NONE,
            'facebook_id' => FILTER_FLAG_NONE,
            'name' => FILTER_FLAG_NONE,
            'designation' => FILTER_FLAG_NONE,
        ];
        $optional = ['facebook_id', 'name', 'designation'];
        $apiParams = filter_var_array($this->requestAttributes, $filterRules); //filter here

        if ($this->validateRequestParams($apiParams, $optional)) {
            Yii::info('Invalid Request ' . json_encode($this->getErrors()), __METHOD__);
            throw new \yii\web\HttpException(400, 'Invalid attributes');
        }

        $contactService = new ContactService();
        $regResponse = $contactService->updateProfile($apiParams);

        if (!$regResponse->getStatus()) {
            throw new \yii\web\HttpException($regResponse->getStatusCode(), $regResponse->getMessage());
        }
        $responseDet = $regResponse->getResponse();
        $response['message'] =  $regResponse->getMessage();
        return $response;
    }
}

?>

<?php

namespace app\modules\v1\services;

use Yii;
use app\components\utils\ResponseFormat;
use app\components\utils\Uid;
use app\modules\v1\services\response\ContactResponse;
use app\models\Contacts;
use app\models\Friends;
use app\models\Visitors;
use yii\db\Query;
use app\models\FilesList;


class ContactService {

    const ACTIVE = 0;
    const INACTIVE = 1;
    const STATUS_MESSAGE = '{"feeling" : "", "status" :  "", "location" : "", "looking" : ""}';

    public function saveContact($apiParams) {

        $response = [];
        $contactResponse = new ContactResponse();
        try {
            $contact = $this->getDetilsByContact($apiParams['phone']);
            if ($contact != null) {
                if ($contact['status'] == 1) {
                    Yii::error('contact already exists with this contact');
                    $contactResponse->setStatus(false);
                    $contactResponse->setStatusCode(409);
                    $contactResponse->setMessage("Contact already exists");
                } else {
                    $contact->phone = $apiParams['phone'];
                    $contact->name = $apiParams['name'];
                    $contact->designation = $apiParams['designation'];
                    $contact->facebook_id = $apiParams['facebook_id'];
                    $contact->status_message = self::STATUS_MESSAGE;
                    $contact->status = 1;
                    $bl = $contact->save();
                    if ($bl == true) {
                        $response['id'] = $contact['id'];
                        $contactResponse->setMessage("Contact added successfully");
                        $contactResponse->setResponse($response);
                    } else {
                        Yii::error('Contact adding failed');
                        $contactResponse->setStatus(false);
                        $contactResponse->setStatusCode(409);
                        $contactResponse->setMessage("Contact adding failed");
                    }
                }
            } else {
                $contact = new Contacts();
                $contact->phone = $apiParams['phone'];
                $contact->name = $apiParams['name'];
                $contact->designation = $apiParams['designation'];
                $contact->facebook_id = $apiParams['facebook_id'];
                $contact->status_message = self::STATUS_MESSAGE;
                $contact->status = 1;
                $bl = $contact->save();

                if ($bl == true) {
                    $response['id'] = $contact['id'];
                    $contactResponse->setMessage("Contact added successfully");
                    $contactResponse->setResponse($response);
                } else {
                    Yii::error('Contact adding failed');
                    $contactResponse->setStatus(false);
                    $contactResponse->setStatusCode(409);
                    $contactResponse->setMessage("Contact adding failed");
                }
            }
        } catch (Exception $exc) {
            Yii::error($exc->getMessage(), 'dbException');
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(424);
            $contactResponse->setMessage("Contact adding failed");
        }
        return $contactResponse;
    }

    private function getDetilsByContact($phone) {
        return Contacts::findOne(['phone' => $phone]);
    }

    private function findById($id) {
        return Contacts::findOne(['id' => $id]);
    }

    public function syncContacts($apiParams) {
        $contactResponse = new ContactResponse();
        $contactDetails = $this->findById($apiParams['id']);
        if ($contactDetails == null || $contactDetails['status'] == 0) {
            Yii::error('Contact doesnot exists with this id');
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(408);
            $contactResponse->setMessage("Contact doesnot exists with this id");
        } else {
            $friends = json_decode($apiParams['contacts'], true);
            for ($i = 0, $cnt = count($friends); $i < $cnt; $i++) {
                $friend = Friends::findOne(['contact_id' => $apiParams['id'], 'phone' => $friends[$i]['phone']]);
                if($friend == null){
                    $friend = new Friends();
                }
                $friend->contact_id = $apiParams['id'];
                $friend->phone = $friends[$i]['phone'];
                $friend->name = $friends[$i]['name'];
                $friend->save();

                $contact = Contacts::findOne(['phone' => $friends[$i]['phone']]);
                if ($contact == NULL) {
                    $contact = new Contacts();
                    $contact->name = $friends[$i]['name'];
                    $contact->phone = $friends[$i]['phone'];
                    $contact->status = 0;
                    $contact->status_message = self::STATUS_MESSAGE;
                    $contact->save();
                }
            }
            $contactResponse->setMessage("Contact sync completed");
        }

        return $contactResponse;
    }
    public function getFriends($id){
        $contactResponse = new ContactResponse();
        $query = new Query;
        $query->select([
		'friends.name AS name', 
		'friends.phone AS phone',
		'contacts.status', 'contacts.visits', 'contacts.designation', 'contacts.id', 'contacts.status_message'
            ]
		)  
	->from('friends')
	->join('INNER JOIN', 'contacts','contacts.phone = friends.phone')
        ->where('friends.contact_id = ' . $id);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $contactResponse->setResponse(array('friends' => $data));
        return $contactResponse;
    }
    public function visitContact($apiParams){
        $contactResponse = new ContactResponse();
        $contact = $this->findById($apiParams['friend_id']);
        if($contact == null){
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(408);
            $contactResponse->setMessage("Visitor id doesnot exists");
            return $contactResponse;
        }
        $visitor = $this->findById($apiParams['id']);
        if($visitor == null){
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(408);
            $contactResponse->setMessage("Contact doesnot exists");
            return $contactResponse;
        }
        
        $visitor = new Visitors();
        $visitor->contact_id = $apiParams['friend_id'];
        $visitor->visitor_contact_id = $apiParams['id'];
        $bl = $visitor->save();
        
        $contact->visits +=  1;
        $bl = $contact->save();
        return $contactResponse;
    }
    public function getProfile($id){
        $contactResponse = new ContactResponse();
        $contact = $this->findById($id);
        
        if($contact == null){
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(408);
            $contactResponse->setMessage("Contact doesnot exists with this id");
            return $contactResponse;
        }
        $contactResponse->setResponse($contact);
        return $contactResponse;
    }
    public function updateStatus($apiParams){
        $contactResponse = new ContactResponse();
        $contact = $this->findById($apiParams['id']);
        if($contact == null){
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(408);
            $contactResponse->setMessage("Contact doesnot exists with this id");
            return $contactResponse;
        }
        $contact->status_message = $apiParams['status-message'];
        $bl = $contact->save();
        if($bl == false){
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(409);
            $contactResponse->setMessage("Status message saving failed");
            return $contactResponse;
        }
        $contactResponse->setMessage('Status message updated successfully');
        return $contactResponse;
    }
    public function saveFiles($apiParams){
        $contactResonse = new ContactResponse();
        $contact = $this->findById($apiParams['id']);
        if($contact == null){
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(408);
            $contactResponse->setMessage("Contact doesnot exists with this id");
            return $contactResponse;
        }
        $files = json_decode($apiParams['files'], true);
        for($i = 0, $cnt = count($files); $i < $cnt;$i++){
            try{
                $file = FilesList::findOne(['contact_id' => $apiParams['id'], 'file_name' => $files[$i]['file_name']]);
                if($file == null){
                    $file = new FilesList();
                }
                $file->contact_id = $apiParams['id'];
                $file->file_name = $files[$i]['file'];
                $file->length = $file[$i]['length'];
                $file->save();
            }catch(Exception $e){
                $contactResponse->setStatus(false);
                $contactResponse->setStatusCode(409);
                $contactResponse->setMessage("Files list saving failed");
                return $contactResponse;
            }
        }
        return $contactResponse;
    }
    public function getFiles($id){
        $contactResponse = new ContactResponse();
        $contact = $this->findById($id);
        if($contact == null){
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(408);
            $contactResponse->setMessage("Contact doesnot exists with this id");
            return $contactResponse;
        }
        $list = FilesList::find(['contact_id' => $id])->asArray()->all();
        if($list == null){
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(409);
            $contactResponse->setMessage("No Files share");
            return $contactResponse;
        }
        $contactResponse->setResponse($list);
        return $contactResponse;
    }
    public function updateProfile($apiParams){
        $contactResponse = new ContactResponse();
        $contact = $this->findById($apiParams['id']);
        if($contact == null){
            $contactResponse->setStatus(false);
            $contactResponse->setStatusCode(408);
            $contactResponse->setMessage("Contact doesnot exists with this id");
            return $contactResponse;
        }
        if($apiParams['facebook_id'] != NULL || $apiParams['name'] != NULL || $apiParams['designation'] != NULL){
            if($apiParams['facebook_id'] != NULL){
                $contact->facebook_id = $apiParams['facebook_id'];
            }
            if($apiParams['name'] != NULL){
                $contact->name = $apiParams['name'];
            }
            if($apiParams['designation'] != NULL){
                $contact->designation = $apiParams['designation'];
            }
            $bl = $contact->save();
            if($bl == false){
                $contactResponse->setStatus(false);
                $contactResponse->setStatusCode(409);
                $contactResponse->setMessage("Profile updating failed");
                return $contactResponse;
            }
        }
        $contactResponse->setMessage('Profile updated successfully');
        return $contactResponse;

    }
}

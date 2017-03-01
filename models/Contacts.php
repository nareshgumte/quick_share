<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contacts".
 *
 * @property integer $id
 * @property string $phone
 * @property string $name
 * @property string $facebook_id
 * @property string $designation
 * @property integer $visits
 * @property string $status_mesage
 * @property string $created_date
 * @property integer $status
 *
 * @property FilesList[] $filesLists
 * @property Friends[] $friends
 * @property Visitors[] $visitors
 * @property Visitors[] $visitors0
 * @property Contacts[] $visitorContacts
 * @property Contacts[] $contacts
 */
class Contacts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visits', 'status'], 'integer'],
            [['status_mesage'], 'required'],
            [['status_mesage'], 'string'],
            [['created_date'], 'safe'],
            [['phone'], 'string', 'max' => 20],
            [['name', 'designation'], 'string', 'max' => 255],
            [['facebook_id'], 'string', 'max' => 64],
            [['phone'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'name' => 'Name',
            'facebook_id' => 'Facebook ID',
            'designation' => 'Designation',
            'visits' => 'Visits',
            'status_mesage' => 'Status Mesage',
            'created_date' => 'Created Date',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilesLists()
    {
        return $this->hasMany(FilesList::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriends()
    {
        return $this->hasMany(Friends::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitors()
    {
        return $this->hasMany(Visitors::className(), ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitors0()
    {
        return $this->hasMany(Visitors::className(), ['visitor_contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitorContacts()
    {
        return $this->hasMany(Contacts::className(), ['id' => 'visitor_contact_id'])->viaTable('visitors', ['contact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contacts::className(), ['id' => 'contact_id'])->viaTable('visitors', ['visitor_contact_id' => 'id']);
    }
}

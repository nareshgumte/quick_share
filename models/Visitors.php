<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visitors".
 *
 * @property integer $contact_id
 * @property integer $visitor_contact_id
 * @property string $visited_date
 *
 * @property Contacts $contact
 * @property Contacts $visitorContact
 */
class Visitors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visitors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id', 'visitor_contact_id'], 'required'],
            [['contact_id', 'visitor_contact_id'], 'integer'],
            [['visited_date'], 'safe'],
            [['contact_id', 'visitor_contact_id'], 'unique', 'targetAttribute' => ['contact_id', 'visitor_contact_id'], 'message' => 'The combination of Contact ID and Visitor Contact ID has already been taken.'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['visitor_contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::className(), 'targetAttribute' => ['visitor_contact_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contact_id' => 'Contact ID',
            'visitor_contact_id' => 'Visitor Contact ID',
            'visited_date' => 'Visited Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contacts::className(), ['id' => 'contact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitorContact()
    {
        return $this->hasOne(Contacts::className(), ['id' => 'visitor_contact_id']);
    }
}

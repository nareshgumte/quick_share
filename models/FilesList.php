<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files_list".
 *
 * @property integer $contact_id
 * @property string $file_name
 * @property integer $length
 * @property string $created_date
 *
 * @property Contacts $contact
 */
class FilesList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id', 'file_name', 'length'], 'required'],
            [['contact_id', 'length'], 'integer'],
            [['created_date'], 'safe'],
            [['file_name'], 'string', 'max' => 255],
            [['contact_id', 'file_name'], 'unique', 'targetAttribute' => ['contact_id', 'file_name'], 'message' => 'The combination of Contact ID and File Name has already been taken.'],
            [['contact_id', 'file_name'], 'unique', 'targetAttribute' => ['contact_id', 'file_name'], 'message' => 'The combination of Contact ID and File Name has already been taken.'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::className(), 'targetAttribute' => ['contact_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contact_id' => 'Contact ID',
            'file_name' => 'File Name',
            'length' => 'Length',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contacts::className(), ['id' => 'contact_id']);
    }
}

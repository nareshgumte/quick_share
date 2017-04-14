<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files_list".
 *
 * @property integer $contact_id
 * @property string $file_path
 * @property string $type
 * @property integer $length
 * @property string $created_date
 * @property string $file_name
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
            [['contact_id', 'file_path', 'type', 'length'], 'required'],
            [['contact_id', 'length'], 'integer'],
            [['type'], 'string'],
            [['created_date'], 'safe'],
            [['file_path', 'file_name'], 'string', 'max' => 255],
            [['contact_id', 'file_path'], 'unique', 'targetAttribute' => ['contact_id', 'file_path'], 'message' => 'The combination of Contact ID and File Path has already been taken.'],
            [['contact_id', 'file_path'], 'unique', 'targetAttribute' => ['contact_id', 'file_path'], 'message' => 'The combination of Contact ID and File Path has already been taken.'],
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
            'file_path' => 'File Path',
            'type' => 'Type',
            'length' => 'Length',
            'created_date' => 'Created Date',
            'file_name' => 'File Name',
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

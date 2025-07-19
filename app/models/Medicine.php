<?php

namespace app\models;

use yii\db\ActiveRecord;

class Medicine extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%medicines}}';
    }

    public function rules()
    {
        return [
            [['name', 'dose', 'description'], 'string', 'max' => 255],
            [['name', 'dose', 'description'], 'required'],
        ];
    }

    public function getReminders()
    {
        return $this->hasMany(Reminder::class, ['medicine_id' => 'id']);
    }
}
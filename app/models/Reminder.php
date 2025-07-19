<?php

namespace app\models;

use yii\db\ActiveRecord;

class Reminder extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%reminders}}';
    }

    public function rules()
    {
        return [
            [['time'], 'safe'],
            [['medicine_id'], 'exist', 'targetClass' => Medicine::class, 'targetAttribute' => ['medicine_id' => 'id']],
            [['time'], 'validateTimeArray'],
            [['begin_date', 'finish_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['comment', 'status'], 'string', 'max' => 255],
            [['time', 'begin_date', 'finish_date', 'comment'], 'required']
        ];
    }

    public function validateTimeArray($attribute)
    {
        if (!is_array($this->$attribute)) {
            $this->addError($attribute, 'Значение должно быть массивом.');
            return;
        }

        if (count($this->$attribute) !== 2) {
            $this->addError($attribute, 'Должно быть ровно два времени.');
            return;
        }

        foreach ($this->$attribute as $time) {
            if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $time)) {
                $this->addError($attribute, "Неверный формат времени: $time. Ожидается формат HH:MM.");
                return;
            }
        }
    }

    public function getMedicine()
    {
        return $this->hasOne(Medicine::class, ['id' => 'medicine_id']);
    }
}
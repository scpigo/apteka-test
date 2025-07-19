<?php

namespace app\models\forms;

use app\models\Medicine;
use app\models\Reminder;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class AddReminderForm extends Model
{
    public $medicine_id;
    public $time;
    public $begin_date;
    public $finish_date;
    public $comment;


    public function rules()
    {
        return [
            [['time'], 'safe'],
            [['medicine_id'], 'exist', 'targetClass' => Medicine::class, 'targetAttribute' => ['medicine_id' => 'id']],
            [['time'], 'validateTimeArray'],
            [['begin_date', 'finish_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['comment'], 'string', 'max' => 255],
            [['medicine_id', 'time', 'begin_date', 'finish_date', 'comment'], 'required'],
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

    public function add()
    {
        if (!$this->validate()) {
            return null;
        }

        $reminder = new Reminder();

        $reminder->medicine_id = $this->medicine_id;
        $reminder->time = $this->time;
        $reminder->begin_date = $this->begin_date;
        $reminder->finish_date = $this->finish_date;
        $reminder->comment = $this->comment;

        $result = $reminder->save();

        return $result ? $reminder : false;
    }
}

<?php

namespace app\models\forms;

use app\models\Medicine;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class AddMedicineForm extends Model
{
    public $name;
    public $dose;
    public $description;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'dose', 'description'], 'required'],
            [['name', 'dose', 'description'], 'string', 'max' => 255],
        ];
    }

    public function add()
    {
        if (!$this->validate()) {
            return null;
        }

        $medicine = new Medicine();

        $medicine->name = $this->name;
        $medicine->dose = $this->dose;
        $medicine->description = $this->description;

        $result = $medicine->save();

        return $result ? $medicine : false;
    }
}

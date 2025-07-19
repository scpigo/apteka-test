<?php

namespace app\controllers;

use Yii;
use app\models\forms\AddMedicineForm;
use app\models\Medicine;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class MedicinesController extends Controller
{
    Public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => \sizeg\jwt\JwtHttpBearerAuth::class,
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

	public function actionList() {
        return $this->asJson(Medicine::find()->select(['id', 'name', 'dose', 'description'])->all());
	}

    public function actionAdd() {
        $form = new AddMedicineForm();

        $form->load(Yii::$app->request->post(), '');

        if ($medicine = $form->add()) {
            return $this->asJson([
                'id' => $medicine->getPrimaryKey(),
                'name' => $medicine->name,
                'dose' => $medicine->dose,
                'description' => $medicine->description,
            ]);
        }
        else
        {
            return $this->asJson([
                'data' => $form->getErrors(),
                'statusText' => 'Ошибка',
            ]);
        }
    }
}

?>
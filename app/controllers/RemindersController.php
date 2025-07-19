<?php

namespace app\controllers;

use Yii;
use app\models\forms\AddReminderForm;
use app\models\Reminder;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class RemindersController extends Controller
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
        $currentDate = date('Y-m-d');
        return $this->asJson(Reminder::find()
            ->where(['<=', 'begin_date', $currentDate])
            ->andWhere(['>=', 'finish_date', $currentDate])
            ->andWhere(['status' => 'new'])
            ->select(['id', 'medicine_id', 'time', 'begin_date', 'finish_date', 'comment'])->all());
	}

    public function actionAdd() {
        $form = new AddReminderForm();

        $form->load(Yii::$app->request->post(), '');

        if ($reminder = $form->add()) {
            return $this->asJson([
                'id' => $reminder->getPrimaryKey(),
                'medicine_id' => $reminder->medicine->getPrimaryKey(),
                'time' => $reminder->time,
                'begin_date' => $reminder->begin_date,
                'finish_date' => $reminder->finish_date,
                'comment' => $reminder->comment,
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

    public function actionTake(int $reminderId)
    {
        $model = Reminder::findOne($reminderId);

        if ($model instanceof Reminder) {
            $model->status = 'taken';
            $model->save();

            return $this->asJson(['statusText' => 'Ок']);
        }

        return $this->asJson(['statusText' => 'Ошибка'], 500);
    }

    public function actionDelete(int $reminderId)
    {
        $model = Reminder::findOne($reminderId);

        if ($model instanceof Reminder) {
            $model->delete();

            return $this->asJson(['statusText' => 'Ок']);
        }

        return $this->asJson(['statusText' => 'Ошибка'], 500);
    }
}

?>
<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AuthController extends Controller
{
    Public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
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

	public function actionRegister() {
		$form = new SignupForm();

		$form->load(Yii::$app->request->post(), '');

		if ($user = $form->signup()) {
			return $this->asJson([
                'user' => [
                    'id' => $user->getPrimaryKey(),
                    'name' => $user->username
                ]
			]);
        }
		else
		{
			return $this->asJson([
                'statusText' => 'Ошибка регистрации'
			], 500);
		}
	}

    public function actionLogin() {
		$form = new LoginForm();

		if ($form->load(Yii::$app->request->post(), '') && $form->login())
		{
			$user = Yii::$app->user->identity;

			$access_token = $this->generateJwt($user);

			return $this->asJson([
                'token' => $access_token->toString(),
				'user' => [
                    'id' => $user->getPrimaryKey(),
                    'name' => $user->username
                ]
			]);
		} else {
			return $this->asJson([
				'data' => $form->getErrors(),
				'statusText' => 'Ошибка авторизации',
			]);
		}
	}

    private function generateJwt(User $user) {
        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner();
        $key = $jwt->getSignerKey();
        $time = new \DateTimeImmutable();

        $jwtParams = Yii::$app->params['jwt'];

        return $jwt->getBuilder()
            ->issuedBy($jwtParams['issuer'])
            ->permittedFor($jwtParams['audience'])
            ->identifiedBy($jwtParams['id'], true)
            ->issuedAt($time)
            ->expiresAt($time->modify('+1 hour'))
            ->withClaim('uid', $user->getPrimaryKey())
            ->getToken($signer, $key);
    }
}

?>
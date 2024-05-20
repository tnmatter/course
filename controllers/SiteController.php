<?php

namespace app\controllers;

use app\actions\ErrorAction;
use app\forms\LoginForm;
use app\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class SiteController extends AbstractController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                    ],
                ],
            ],
        ];
    }

    public function handleUnauthorizedException(UnauthorizedHttpException $e): bool
    {
        return true;
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionLogin(): Response|string
    {
        $loginForm = new LoginForm();
        if ($loginForm->load($this->request->post())) {
            if ($loginForm->login()) {
                return $this->redirect('index');
            }
        }
        $this->layout = 'login';
        return $this->render('login', compact('loginForm'));
    }

    public function actionSignup(): Response|string
    {
        $signupForm = new SignupForm();
        if ($signupForm->load($this->request->post())) {
            if ($signupForm->signup()) {
                return $this->redirect('index');
            }
        }
        $this->layout = 'login';
        return $this->render('signup', compact('signupForm'));
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->redirect('login');
    }
}

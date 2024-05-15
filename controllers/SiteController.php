<?php

namespace app\controllers;

class SiteController extends AbstractController
{
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionError(): void
    {
    }
}

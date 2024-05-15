<?php

namespace app\controllers;

class SiteController extends AbstractController
{
    public function actionIndex(): string
    {
        echo 1;
        return $this->render('index');
    }
}

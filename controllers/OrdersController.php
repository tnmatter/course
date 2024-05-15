<?php

namespace app\controllers;

use app\controllers\AbstractController;

class OrdersController extends AbstractController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
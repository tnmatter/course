<?php

namespace app\controllers;

use app\controllers\AbstractController;
use app\filters\OrdersFilter;
use app\models\Order;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class OrdersController extends AbstractController
{
    public function actionIndex(): string
    {
        $filter = new OrdersFilter();
        return $this->render('index', compact('filter'));
    }

    public function actionCreate(): Response|string
    {
        $order = new Order();
        if ($order->load($this->request->post())) {
            $order->courier_id = \Yii::$app->user->identity->id;
            $this->performAjaxValidation($order);
            if ($order->save()) {
                $this->setSaveFlash();
                return $this->redirect('/orders');
            } else {
                $this->setValidationFlash();
            }
        }
        return $this->render('create', compact('order'));
    }

    public function actionUpdate(int $id): Response|string
    {
        $order = Order::findOne($id);
        if ($order !== null) {
            $data = $this->request->post();
            if ($order->load($data)) {
                $this->performAjaxValidation($order);
                if ($order->save()) {
                    $this->setSaveFlash();
                    return $this->redirect('/orders');
                } else {
                    $this->setValidationFlash();
                }
            }
            return $this->render('update', compact('order'));
        }
        throw new NotFoundHttpException();
    }
}
<?php

namespace app\controllers;

use app\filters\OrdersFilter;
use app\forms\OrderCreateForm;
use app\forms\OrderUpdateForm;
use app\models\Order;
use Yii;
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
        $orderCreateForm = new OrderCreateForm();
        if ($orderCreateForm->load($this->request->post())) {
            $orderCreateForm->courier_id = Yii::$app->user->identity->id;
            $this->performAjaxValidation($orderCreateForm);
            if ($orderCreateForm->save()) {
                $this->setSaveFlash();
                return $this->redirect('/orders');
            } else {
                $this->setValidationFlash();
            }
        }
        return $this->render('create', compact('orderCreateForm'));
    }

    public function actionUpdate(int $id): Response|string
    {
        $order = Order::findOne($id);
        if ($order !== null) {
            $orderUpdateForm = new OrderUpdateForm($order);
            $data = $this->request->post();
            if ($orderUpdateForm->load($data)) {
                $this->performAjaxValidation($orderUpdateForm);
                if ($orderUpdateForm->save()) {
                    $this->setSaveFlash();
                    return $this->redirect('/orders');
                } else {
                    $this->setValidationFlash();
                }
            }
            return $this->render('update', compact('orderUpdateForm'));
        }
        throw new NotFoundHttpException();
    }

    public function actionView(int $id): Response|string
    {
        $order = Order::findOne($id);
        if ($order !== null) {
            if ($order->load($this->request->post())) {
                $this->performAjaxValidation($order);
                if ($order->save()) {
                    $this->setSaveFlash();
                    return $this->redirect('/orders');
                } else {
                    $this->setValidationFlash();
                }
            }
            return $this->render('view', compact('order'));
        }
        throw new NotFoundHttpException();
    }
}
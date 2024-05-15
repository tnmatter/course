<?php

use app\models\Order;
use yii\web\View;

/**
 * @var Order $order
 * @var View $this
 */

$title = Yii::t('app', 'Обновление заказа');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => '/orders'];
$this->params['breadcrumbs'][] = ['label' => $order->id, 'url' => ['/orders', 'id' => $order->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Обновление'), 'url' => "/orders/$order->id/update"];
echo $this->render('form', compact('order', 'title'));

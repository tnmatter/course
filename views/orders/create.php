<?php

use app\models\Order;
use yii\web\View;

/**
 * @var Order $order
 * @var View $this
 */

$title = Yii::t('app', 'Создание заказа');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => '/orders'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Создание'), 'url' => '/orders/create'];
echo $this->render('form', compact('order', 'title'));

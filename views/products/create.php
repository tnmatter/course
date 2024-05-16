<?php

use app\models\Product;
use yii\web\View;

/**
 * @var Product $product
 * @var View $this
 */

$title = Yii::t('app', 'Добавление товара');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Товары'), 'url' => '/products'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Создание'), 'url' => '/products/create'];
echo $this->render('form', compact('product', 'title'));

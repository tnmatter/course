<?php

use app\models\Product;
use yii\web\View;

/**
 * @var Product $product
 * @var View $this
 */

$title = Yii::t('app', 'Обновление товара');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Товары'), 'url' => '/products'];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['/products', 'id' => $product->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Обновление'), 'url' => ['/products/update', 'id' => $product->id]];
echo $this->render('form', compact('product', 'title'));

<?php

use app\filters\OrdersFilter;
use app\models\Order;
use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\web\View;

/**
 * @var OrdersFilter $filter
 * @var View $this
 */

$this->title = Yii::t('app', 'Заказы');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => '/orders'];
?>

<?= Html::tag('h3', Yii::t('app', 'Заказы')); ?>
    <div style="padding: 10px 0">
        <?= Html::a(
            Html::button(
                Html::tag('i', '', ['class' => 'fas fa-plus', 'style' => 'margin-right: 5px;'])
                . Html::tag('span', Yii::t('app', 'Создать')),
                ['class' => 'btn btn-success'],
            ),
            '/orders/create',
        ); ?>
    </div>

<?= GridView::widget([
    'dataProvider' => $filter->search(),
    'columns' => [
        [
            'attribute' => 'id',
        ],
        [
            'class' => ActionColumn::class,
            'template' => '{view} {update}',
            'buttons' => [
                'view' => fn($url, Order $o) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-eye text-success']),
                    $url,
                    ['title' => Yii::t('app', 'Просмотреть')],
                ),
                'update' => fn($url, Order $o) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-pencil']),
                    $url,
                    ['title' => Yii::t('app', 'Обновить')],
                ),
            ],
        ],
        [
            'attribute' => 'customer_name',
        ],
        [
            'attribute' => 'customer_phone',
            'value' => fn(Order $o) => $o->customerPhoneHtml,
            'format' => 'raw',
        ],
        [
            'attribute' => 'courier_id',
            'value' => fn(Order $o) => $o->courier->name,
        ],
        [
            'attribute' => 'status',
            'value' => fn(Order $o) => Html::tag(
                'div',
                $o->status->getName(),
                ['class' => 'badge ' . $o->status->getTextColorClass()->getTextClass()],
            ),
            'format' => 'raw',
        ],
    ],
]);

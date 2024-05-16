<?php

use app\filters\ProductsFilter;
use app\models\Product;
use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\web\View;
use app\helpers\HHtml;


/**
 * @var ProductsFilter $filter
 * @var View $this
 */

$this->title = Yii::t('app', 'Товары');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Товары'), 'url' => '/products'];
?>

<?= Html::tag('h3', Yii::t('app', 'Товары')); ?>
    <div style="padding: 10px 0">
        <?= Html::a(
            Html::button(
                Html::tag('i', '', ['class' => 'fas fa-plus', 'style' => 'margin-right: 5px;'])
                . Html::tag('span', Yii::t('app', 'Добавить')),
                ['class' => 'btn btn-success'],
            ),
            '/products/create',
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
                'view' => fn($url, Product $o) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-eye text-success']),
                    $url,
                    ['title' => Yii::t('app', 'Просмотреть')],
                ),
                'update' => fn($url, Product $o) => Html::a(
                    Html::tag('i', '', ['class' => 'fas fa-pencil']),
                    $url,
                    ['title' => Yii::t('app', 'Обновить')],
                ),
            ],
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'description',
        ],
        [
            'attribute' => 'count',
        ],
        [
            'attribute' => 'created_at',
            'value' => fn(Product $p) => HHtml::dateUi($p->created_at),
            'format' => 'raw',
        ],
        [
            'attribute' => 'updated_at',
            'value' => fn(Product $p) => HHtml::dateUi($p->updated_at),
            'format' => 'raw',
        ],
    ],
]);


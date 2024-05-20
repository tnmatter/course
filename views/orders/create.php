<?php

use app\forms\OrderCreateForm;
use app\helpers\HHtml;
use app\widgets\ActiveForm;
use app\widgets\DateTimePicker;
use app\widgets\Select2;
use borales\extensions\phoneInput\PhoneInput;
use unclead\multipleinput\MultipleInput;
use yii\web\JsExpression;
use yii\web\View;

/**
 * @var OrderCreateForm $orderCreateForm
 * @var View $this
 * @var string $title
 */

$title = Yii::t('app', 'Создание заказа');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => '/orders'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Создание'), 'url' => '/orders/create'];

$form = ActiveForm::begin();
?>
<div class="row">
    <h4><?= $title; ?></h4>
    <div class="col-12">
        <?= $form->errorSummary([$orderCreateForm]) ?>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header fw-bolder"><?= Yii::t('app', 'Информация о заказчике'); ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <?= $form->field($orderCreateForm, 'customer_name')->textInput(); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($orderCreateForm, 'customer_phone')->widget(
                            PhoneInput::class,
                        ); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($orderCreateForm, 'address')->textInput(); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($orderCreateForm, 'deliver_from')->widget(DateTimePicker::class) ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($orderCreateForm, 'deliver_to')->widget(DateTimePicker::class) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header fw-bolder"><?= Yii::t('app', 'Товары'); ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <?= $form->field($orderCreateForm, 'products')->widget(
                            MultipleInput::class,
                            [
                                'data' => $orderCreateForm->products,
                                'iconSource' => MultipleInput::ICONS_SOURCE_FONTAWESOME,
                                'columns' => [
                                    [
                                        'name' => 'product_id',
                                        'title' => Yii::t('app', 'Товар'),
                                        'type' => Select2::class,
                                        'options' => [
                                            'pluginOptions' => [
                                                'ajax' => [
                                                    'url' => '/products/filter',
                                                    'minimumResultsForSearch' => -1,
                                                    'cache' => true,
                                                    'delay' => 500,
                                                    'data' => new JsExpression(
                                                        <<<JS
function (params) {
    return {
        query: params.term,
        page: params.page || 1
    };
}
JS,
                                                    ),
                                                ],
                                                'templateResult' => new JsExpression('function(data) {return data.html || data.text;}'),
                                                'templateSelection' => new JsExpression('function(data) {return data.text;}'),
                                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                            ],
                                            'options' => ['placeholder' => Yii::t('app', 'Выберите товар...')],
                                        ],
                                        'columnOptions' => ['class' => 'w-75'],
                                    ],
                                    [
                                        'name' => 'count',
                                        'type' => 'textInput',
                                        'title' => Yii::t('app', 'Кол-во'),
                                        'options' => ['placeholder' => '1...', 'type' => 'number', 'min' => 1],
                                    ],
                                ],
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <?= HHtml::formButtonGroup(false); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

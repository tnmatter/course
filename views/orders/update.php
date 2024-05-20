<?php

use app\enum\OrderStatusEnum;
use app\forms\OrderUpdateForm;
use app\helpers\HEnum;
use app\helpers\HHtml;
use app\widgets\ActiveForm;
use app\widgets\DateTimePicker;
use app\widgets\Select2;
use kartik\rating\StarRating;
use unclead\multipleinput\MultipleInput;
use yii\web\JsExpression;
use yii\web\View;

/**
 * @var OrderUpdateForm $orderUpdateForm
 * @var View $this
 */

$title = Yii::t('app', 'Обновление заказа');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => '/orders'];
$this->params['breadcrumbs'][] = ['label' => $orderUpdateForm->id, 'url' => ['/orders', 'id' => $orderUpdateForm->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Обновление'), 'url' => "/orders/$orderUpdateForm->id/update"];

$form = ActiveForm::begin();
?>
    <div class="row">
        <h4><?= $title; ?></h4>
        <div class="col-12">
            <?= $form->errorSummary([$orderUpdateForm]) ?>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder"><?= Yii::t('app', 'Информация о заказчике'); ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($orderUpdateForm, 'address')->textInput(); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($orderUpdateForm, 'deliver_from')->widget(DateTimePicker::class) ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($orderUpdateForm, 'deliver_to')->widget(DateTimePicker::class) ?>
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
                            <?= $form->field($orderUpdateForm, 'products')->widget(
                                MultipleInput::class,
                                [
                                    'data' => $orderUpdateForm->products,
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
                                ],
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder"><?= Yii::t('app', 'Остальное'); ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($orderUpdateForm, 'status')->widget(
                                Select2::class,
                                ['data' => HEnum::getCasesList(OrderStatusEnum::class, withEmptyChoice: false)],
                            ); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($orderUpdateForm, 'delivered_at')->widget(
                                DateTimePicker::class,
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bolder"><?= Yii::t('app', 'Обратная связь'); ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($orderUpdateForm, 'feedback')->textarea(); ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($orderUpdateForm, 'feedback_assessment')->widget(
                                StarRating::class,
                                ['pluginOptions' => ['step' => 1]],
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <?= HHtml::formButtonGroup(true); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
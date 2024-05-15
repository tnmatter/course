<?php

use app\models\Order;
use app\widgets\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;
use yii\web\View;

/**
 * @var Order $order
 * @var View $this
 * @var string $title
 */

$form = ActiveForm::begin();
?>
<div class="row">
    <h4><?= $title; ?></h4>
    <div class="col-12">
        <?= $form->errorSummary([$order]) ?>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header fw-bolder"><?= Yii::t('app', 'Информация о заказчике'); ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <?= $form->field($order, 'customer_name')->textInput(); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($order, 'customer_phone')->widget(
                            PhoneInput::class,
                        ); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($order, 'deliver_from')->widget(\app\widgets\DateTimePicker::class) ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($order, 'deliver_to')->widget(\app\widgets\DateTimePicker::class) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

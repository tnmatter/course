<?php

use app\helpers\HHtml;
use app\models\Product;
use app\widgets\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;
use yii\web\View;
use yii\bootstrap5\Html;


/**
 * @var Product $product
 * @var View $this
 * @var string $title
 */

$form = ActiveForm::begin();
?>
<div class="row">
    <h4><?= $title; ?></h4>
    <div class="col-12">
        <?= $form->errorSummary([$product]) ?>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header fw-bolder"><?= Yii::t('app', 'Информация о товаре'); ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <?= $form->field($product, 'name')->textInput(); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($product, 'description')->textarea(); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($product, 'count')->textInput()?>
                    </div>
                    <div class="col-12">
                        <?= HHtml::formButtonGroup(!$product->isNewRecord); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

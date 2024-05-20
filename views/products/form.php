<?php

use app\enum\FileUploadTypeEnum;
use app\helpers\HHtml;
use app\models\Product;
use app\widgets\ActiveForm;
use app\widgets\FileUploadWidget;
use yii\web\View;


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
                        <?= $form->field($product, 'count')->textInput(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header fw-bolder"><?= Yii::t('app', 'Информация о товаре'); ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <?= $form->field($product, 'avatar')->input('url')
                            ->hint(Yii::t('app', 'Можно указать ссылку на картинку')); ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($product, 'avatar')->widget(
                            FileUploadWidget::class,
                            ['type' => FileUploadTypeEnum::PRODUCT_AVATAR->value, 'extensions' => ['jpg', 'png', 'jpeg']],
                        )->hint(Yii::t('app', 'Можно загрузить картинку вручную')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <?= HHtml::formButtonGroup(!$product->isNewRecord); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

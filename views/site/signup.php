<?php

use app\forms\SignupForm;
use borales\extensions\phoneInput\PhoneInput;
use yii\bootstrap5\Html;
use yii\web\View;
use app\widgets\ActiveForm;

/**
 * @var SignupForm $signupForm
 * @var View $this
 */

$form = ActiveForm::begin();
?>
<div class="row p-4">
    <h2><?= Yii::t('app', 'Регистрация'); ?></h2>
    <div style="margin-top: -10px;">
        <?= Yii::t('app', 'Уже есть аккаунт?'); ?> <a href="/site/login"><?= Yii::t('app', 'войти'); ?></a>
    </div>
    <div class="col-md-6 col-12">
        <?= $form->field($signupForm, 'phone')->widget(
            PhoneInput::class,
        ); ?>
    </div>
    <div class="col-md-6 col-12">
        <?= $form->field($signupForm, 'name')->textInput(); ?>
    </div>
    <div class="col-7">
        <?= $form->field($signupForm, 'password')->passwordInput([
            'pluginOptions' => ['clientValidation' => true]
        ]); ?>
    </div>
    <div class="col-7">
        <?= $form->field($signupForm, 'repeat_password')->passwordInput([
            'pluginOptions' => ['clientValidation' => true]
        ]); ?>
    </div>
    <div class="col-12">
        <?= Html::submitButton(Yii::t('app', 'Зарегистрироваться'), ['class' => 'btn btn-success']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

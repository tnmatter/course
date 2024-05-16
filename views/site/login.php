<?php

use app\forms\LoginForm;
use borales\extensions\phoneInput\PhoneInput;
use yii\bootstrap5\Html;
use yii\web\View;
use app\widgets\ActiveForm;

/**
 * @var LoginForm $loginForm
 * @var View $this
 */

$form = ActiveForm::begin();
?>
<div class="row p-4">
    <h2><?= Yii::t('app', 'Войти в аккаунт'); ?></h2>
    <div style="margin-top: -10px;">
        <?= Yii::t('app', 'Нет аккаунта?'); ?> <a href="/site/signup"><?= Yii::t('app', 'зарегистрироваться'); ?></a>
    </div>
    <div class="col-7">
        <?= $form->field($loginForm, 'phone')->widget(
            PhoneInput::class,
        ); ?>
    </div>
    <div class="col-7">
        <?= $form->field($loginForm, 'password')->passwordInput(); ?>
    </div>
    <div class="col-12">
        <?= Html::submitButton(Yii::t('app', 'Войти'), ['class' => 'btn btn-success']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

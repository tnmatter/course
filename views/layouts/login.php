<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php
$this->beginBody() ?>

<main id="main" class="flex-shrink-0 h-100" role="main">
    <div class="container d-flex justify-content-center align-items-center h-100">
        <?php
        if (!empty($this->params['breadcrumbs'])) { ?>
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'],
                'homeLink' => ['label' => Yii::t('app', 'Главная'), 'url' => '/site/index'],
            ]); ?>
        <?php } ?>
        <?= Alert::widget() ?>
        <div class="login-box">
            <?= $content ?>
        </div>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; My Company</div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::t('app', '2024 г.'); ?></div>
        </div>
    </div>
</footer>

<?php
$this->endBody() ?>
</body>
</html>
<?php
$this->endPage() ?>

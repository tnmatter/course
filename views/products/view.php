<?php

use app\models\Product;
use yii\web\View;

/**
 * @var Product $product
 * @var View $this
 * @var string $title
 */

$title = Yii::t('app', 'Просмотр товара');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Товары'), 'url' => '/products'];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['/products', 'id' => $product->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Просмотреть'), 'url' => ['/products/view', 'id' => $product->id]];

?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="my-4"><?= $title; ?></h2>
        </div>
        <div class="col-md-6 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold"><?= Yii::t('app', 'Информация о товаре'); ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Название:'); ?></strong> <?= htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Описание:'); ?></strong> <?= nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?></p>
                        </div>
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Количество:'); ?></strong> <?= htmlspecialchars($product->count, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold"><?= Yii::t('app', 'Аватар'); ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Аватар (URL):'); ?></strong> <?= htmlspecialchars($product->avatar, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="col-12">
                            <?php if ($product->avatar): ?>
                                <img src="<?= htmlspecialchars($product->avatar, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded">
                            <?php else: ?>
                                <p><?= Yii::t('app', 'Аватар не указан'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
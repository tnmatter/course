<?php

use app\models\Order;
use yii\web\View;
use yii\bootstrap5\Html;

/**
 * @var Order $order
 * @var View $this
 * @var string $title
 */

$title = Yii::t('app', 'Просмотр заказа');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => '/orders'];
$this->params['breadcrumbs'][] = ['label' => $order->id, 'url' => ['/orders', 'id' => $order->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Просмотреть'), 'url' => ['/orders/id/view', 'id' => $order->id]];
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="my-4"><?= $title; ?></h2>
        </div>
        <div class="col-md-6 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold"><?= Yii::t('app', 'Информация о заказчике'); ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Имя заказчика:'); ?></strong> <?= htmlspecialchars($order->customer_name, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Телефон заказчика:'); ?></strong> <?= $order->getCustomerPhoneHtml(); ?></p>
                        </div>
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Адрес доставки:'); ?></strong> <?= htmlspecialchars($order->address, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Доставить с:'); ?></strong> <?= htmlspecialchars($order->deliver_from->format('Y-m-d H:i:s'), ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Доставить до:'); ?></strong> <?= htmlspecialchars($order->deliver_to->format('Y-m-d H:i:s'), ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold"><?= Yii::t('app', 'Товары'); ?></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Товар'); ?></th>
                                <th><?= Yii::t('app', 'Кол-во'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($order->orderProducts as $orderProduct): ?>
                                <tr>
                                    <td><?= htmlspecialchars($orderProduct->product->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($orderProduct->count, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold"><?= Yii::t('app', 'Остальное'); ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Статус:'); ?></strong> <?= $order->status->getName(); ?></p>
                        </div>
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Дата доставки:'); ?></strong> <?= htmlspecialchars($order->delivered_at ? $order->delivered_at->format('Y-m-d H:i:s') : Yii::t('app', 'Не доставлено'), ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold"><?= Yii::t('app', 'Обратная связь'); ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Отзыв:'); ?></strong> <?= nl2br(htmlspecialchars($order->feedback, ENT_QUOTES, 'UTF-8')); ?></p>
                        </div>
                        <div class="col-12 mb-2">
                            <p><strong><?= Yii::t('app', 'Оценка:'); ?></strong> <?= htmlspecialchars($order->feedback_assessment, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
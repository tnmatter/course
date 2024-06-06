<?php

namespace app\forms;

use app\enum\OrderStatusEnum;
use app\helpers\HArray;
use app\helpers\HEnum;
use app\models\Order;
use app\models\OrderProduct;
use app\models\Product;
use app\validators\ArrayValidator;
use DateTimeImmutable;
use Exception;
use Yii;
use yii\base\Model;
use yii\validators\NumberValidator;

class OrderUpdateForm extends Model
{
    public int $id;
    public string|null $address = null;
    public string|null $deliver_from = null;
    public string|null $deliver_to = null;
    public array|null $products = null;
    public string|null $status = null;
    public string|null $delivered_at = null;
    public string|null $feedback = null;
    public string|null $feedback_assessment = null;

    /** @var OrderProduct[] $oldProducts */
    private array $oldProducts;
    private Order $order;

    public function __construct(Order $order)
    {
        parent::__construct();
        $this->order = $order;
        $this->oldProducts = HArray::index($order->orderProducts, fn(OrderProduct $op) => $op->product_id);
        $this->products = array_map(fn(OrderProduct $op) => ['product_id' => $op->product_id, 'count' => $op->count], $order->orderProducts);
        $this->id = $order->id;
        $this->address = $order->address;
        $this->deliver_from = $order->deliver_from->format('Y-m-d H:i');
        $this->deliver_to = $order->deliver_to->format('Y-m-d H:i');
        $this->status = $order->status->value;
        $this->delivered_at = $order->delivered_at?->format('Y-m-d H:i:s');
        $this->feedback = $order->feedback;
        $this->feedback_assessment = $order->feedback_assessment;
    }

    public function rules(): array
    {
        return [
            [['address', 'deliver_from', 'deliver_to', 'products', 'status'], 'required'],
            [['address', 'status', 'feedback'], 'string'],
            [['feedback_assessment'], 'integer', 'min' => 0],
            [['status'], 'in', 'range' => HEnum::getCases(OrderStatusEnum::class)],
            [['deliver_from'], $this->validateDates(...)],
            [['products'], $this->validateProducts(...)],
            [['delivered_at'], 'datetime', 'format' => 'php:Y-m-d H:i'],
        ];
    }

    public function validateDates(): void
    {
        $to = $from = null;
        try {
            $from = new DateTimeImmutable($this->deliver_from);
        } catch (Exception $e) {
            $this->addError('deliver_from', Yii::t('app', 'Некорректное время начала доставки'));
            return;
        }
        try {
            $to = new DateTimeImmutable($this->deliver_to);
        } catch (Exception $e) {
            $this->addError('deliver_to', Yii::t('app', 'Некорректное время начала доставки'));
            return;
        }
        if ($to->getTimestamp() <= $from->getTimestamp()) {
            $this->addError('deliver_to', Yii::t('app', 'Время окончания доставки должно быть больше времени начала'));
        }
    }

    public function validateProducts(): void
    {
        $validator = new ArrayValidator();
        $validator->format = [['product_id' => new NumberValidator(['min' => 0]), 'count' => new NumberValidator(['min' => 1])]];
        if ($validator->validate($this->products)) {
            $changes = [];
            foreach ($this->products as $product) {
                $oldProduct = $this->oldProducts[$product['product_id']] ?? null;
                if ($oldProduct === null || $oldProduct->count !== $product['count']) {
                    $changes[] = [
                        'product_id' => $oldProduct ? $oldProduct['product_id'] : $product['product_id'] ,
                        'count' => $oldProduct ? $product['count'] - $oldProduct->count : $product['count'],
                        'new' => $oldProduct === null,
                    ];
                }
            }
            if ($changes) {
                $productIds = array_column($changes, 'product_id');
                /** @var Product[] $dbProducts */
                $dbProducts = Product::find()->where(['id' => $productIds])->indexBy('id')->all();
                if (count($dbProducts) !== count($this->products)) {
                    $this->addError('products', Yii::t('app', 'Некоторых товаров не существует'));
                } else {
                    foreach ($changes as $product) {
                        $dbProduct = $dbProducts[$product['product_id']];
                        if ($dbProduct->count === 0) {
                            if ($product['new']) {
                                $this->addError(
                                    'products',
                                    Yii::t('app', '{p} нет в наличии', ['p' => $dbProduct->name])
                                );
                            } else {
                                $this->addError(
                                    'products',
                                    Yii::t('app', '{p} нет в большем количестве', ['p' => $dbProduct->name])
                                );
                            }
                        } elseif ($dbProduct->count < $product['count']) {
                            if ($product['new']) {
                                $this->addError(
                                    'products',
                                    Yii::t(
                                        'app',
                                        '{p} нет в количестве {c, plural, one{# штуки} many{# штук} other{# штук}}',
                                        ['p' => $dbProduct->name, 'c' => $product['count']],
                                    ),
                                );
                            } else {
                                $this->addError(
                                    'products',
                                    Yii::t(
                                        'app',
                                        '{p} нет еще в количестве {c, plural, one{# штуки} many{# штук} other{# штук}}',
                                        ['p' => $dbProduct->name, 'c' => $product['count']],
                                    ),
                                );
                            }
                        }
                    }
                }
            }
        } else {
            $this->addError('products', Yii::t('app', 'Некоторые товары неверны'));
        }
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'address' => Yii::t('app', 'Адрес доставки'),
            'deliver_from' => Yii::t('app', 'Доставить с'),
            'deliver_to' => Yii::t('app', 'Доставить до'),
            'products' => Yii::t('app', 'Товары'),
            'status' => Yii::t('app', 'Статус'),
            'delivered_at' => Yii::t('app', 'Доставлено'),
            'feedback' => Yii::t('app', 'Комментарий'),
            'feedback_assessment' => Yii::t('app', 'Оценка'),
        ];
    }

    public function save(): bool
    {
        if ($this->validate()) {
            $this->order->setAttributes([
                'address' => $this->address,
                'deliver_from' => new DateTimeImmutable($this->deliver_from),
                'deliver_to' => new DateTimeImmutable($this->deliver_to),
                'status' => OrderStatusEnum::from($this->status),
                'delivered_at' => $this->delivered_at ? new DateTimeImmutable($this->delivered_at) : null,
                'feedback' => $this->feedback,
                'feedback_assessment' => $this->feedback_assessment,
            ]);
            if ($this->order->save()) {
                $products = HArray::index($this->products, 'product_id');
                /** @var OrderProduct[] $existProducts */
                $existProducts = OrderProduct::find()
                    ->where(['order_id' => $this->order->id])
                    ->indexBy('product_id')
                    ->all();
                foreach ($products as $id => $product) {
                    $updateProduct = $existProducts[$id] ?? new OrderProduct();
                    $updateProduct->count = $product['count'];
                    $updateProduct->order_id = $this->order->id;
                    $updateProduct->product_id = $product['product_id'];
                    if (!$updateProduct->save()) {
                        $this->addError('products', Yii::t('app', 'Некоторые товары неверны'));
                        return false;
                    }
                }
                foreach (array_diff_key($existProducts, $products) as $productToDelete) {
                    $productToDelete->delete();
                }
                return true;
            } else {
                $this->addError('id', Yii::t('app', 'Не удалось сохранить заказ'));
            }
        }
        return false;
    }
}

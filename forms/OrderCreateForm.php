<?php

namespace app\forms;

use app\enum\OrderStatusEnum;
use app\models\Order;
use app\models\OrderProduct;
use app\models\Product;
use app\validators\ArrayValidator;
use DateTimeImmutable;
use Exception;
use Yii;
use yii\base\Model;
use yii\validators\NumberValidator;

class OrderCreateForm extends Model
{
    public string|null $customer_name = null;
    public string|null $customer_phone = null;
    public string|null $address = null;
    public string|null $deliver_from = null;
    public string|null $deliver_to = null;
    public array|null $products = null;
    public int|null $courier_id = null;

    public function attributeLabels(): array
    {
        return [
            'customer_name' => Yii::t('app', 'Имя заказчика'),
            'customer_phone' => Yii::t('app', 'Телефон заказчика'),
            'address' => Yii::t('app', 'Адрес доставки'),
            'deliver_from' => Yii::t('app', 'Доставить с'),
            'deliver_to' => Yii::t('app', 'Доставить до'),
            'products' => Yii::t('app', 'Товары'),
        ];
    }

    public function rules(): array
    {
        return [
            [['customer_name', 'customer_phone', 'address', 'deliver_from', 'deliver_to', 'products', 'courier_id'], 'required'],
            [['courier_id'], 'integer'],
            [['customer_name'], 'string', 'max' => 255],
            [['customer_phone'], 'string', 'max' => 15],
            [['address'], 'string'],
            [['deliver_from'], $this->validateDates(...)],
            [['products'], $this->validateProducts(...)],
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
            $productIds = array_unique(array_column($this->products, 'product_id'));
            if (count($productIds) !== count($this->products)) {
                $this->addError('products', Yii::t('app', 'Укажите каждый товар только в 1 строчке'));
            } else {
                /** @var Product[] $dbProducts */
                $dbProducts = Product::find()->where(['id' => $productIds])->indexBy('id')->all();
                if (count($dbProducts) !== count($this->products)) {
                    $this->addError('products', Yii::t('app', 'Некоторых товаров не существует'));
                } else {
                    foreach ($this->products as $product) {
                        $dbProduct = $dbProducts[$product['product_id']];
                        if ($dbProduct->count === 0) {
                            $this->addError('products', Yii::t('app', '{p} нет в наличии', ['p' => $dbProduct->name]));
                        } elseif ($dbProduct->count < $product['count']) {
                            $this->addError(
                                'products',
                                Yii::t(
                                    'app',
                                    '{p} нет в количестве {c, plural, one{# штуки} many{# штук} other{# штук}}',
                                    ['p' => $dbProduct->name, 'c' => $product['count']],
                                ),
                            );
                        }
                    }
                }
            }
        } else {
            $this->addError('products', Yii::t('app', 'Некоторые товары неверны'));
        }
    }

    public function save(): bool
    {
        if ($this->validate()) {
            return Order::getDb()->transaction(
                function ($db) {
                    $order = new Order([
                        'customer_name' => $this->customer_name,
                        'customer_phone' => $this->customer_phone,
                        'address' => $this->address,
                        'deliver_from' => new DateTimeImmutable($this->deliver_from),
                        'deliver_to' => new DateTimeImmutable($this->deliver_to),
                        'courier_id' => $this->courier_id,
                        'status' => OrderStatusEnum::Draft,
                    ]);
                    if ($order->save()) {
                        $products = array_map(
                            fn($product) => new OrderProduct([
                                'product_id' => $product['product_id'],
                                'count' => $product['count'],
                                'order_id' => $order->id,
                            ]),
                            $this->products,
                        );
                        foreach ($products as $product) {
                            if (!$product->save()) {
                                $this->addError('products', Yii::t('app', 'Некоторые товары неверны'));
                                return false;
                            }
                        }
                        return true;
                    }
                    return false;
                },
            );
        }
        return false;
    }
}

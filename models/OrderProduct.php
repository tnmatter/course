<?php

namespace app\models;

use app\db\AbstractPgModel;
use app\enum\OrderStatusEnum;
use app\validators\TypeValidator;
use borales\extensions\phoneInput\PhoneInputValidator;
use DateTimeImmutable;
use Yii;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property int $product_id
 * @property int $order_id
 * @property int $count
 */
class OrderProduct extends AbstractPgModel
{
    public static function tableName(): string
    {
        return 'order_product';
    }
    
    public function rules(): array
    {
        return [
            [['product_id', 'order_id', 'count'], 'required'],
            [['product_id', 'order_id', 'count'], 'integer'],
            [['product_id'], 'exist', 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id'], 'skipOnError' => false],
            [['order_id'], 'exist', 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id'], 'skipOnError' => false],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Товар'),
            'order_id' => Yii::t('app', 'Заказ'),
            'count' => Yii::t('app', 'Количество'),
        ];
    }
}

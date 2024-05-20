<?php

namespace app\models;

use app\db\AbstractPgModel;
use DateTimeImmutable;
use Yii;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property int $product_id
 * @property int $order_id
 * @property int $count
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 *
 * @property Product $product
 * @property Order $order
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
            'created_at' => Yii::t('app', 'Создан'),
            'updated_at' => Yii::t('app', 'Обновлен'),
        ];
    }

    public function afterSave($insert, $changedAttributes): void
    {
        $countDiff = $this->count - ($changedAttributes['count'] ?? $this->count);
        $this->product->updateCounters(['count' => -$countDiff]);
        parent::afterSave($insert, $changedAttributes);
    }

    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }
}

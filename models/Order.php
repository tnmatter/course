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
 * @property string $customer_name
 * @property string $customer_phone
 * @property string $address
 * @property DateTimeImmutable $deliver_from
 * @property DateTimeImmutable $deliver_to
 * @property int $courier_id
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 * @property OrderStatusEnum $status
 * @property DateTimeImmutable|null $delivered_at
 * @property string|null $feedback
 * @property int|null $feedback_assessment
 * @property User $courier
 * @property string $customerPhoneHtml
 */
class Order extends AbstractPgModel
{
    public static function tableName(): string
    {
        return 'order';
    }
    
    public function rules(): array
    {
        return [
            [['customer_name', 'customer_phone', 'address', 'deliver_from', 'deliver_to'], 'required'],
            [['customer_name', 'address'], 'string', 'max' => 255],
            [['customer_phone'], PhoneInputValidator::class],
            [['deliver_from', 'deliver_to', 'delivered_at'], TypeValidator::class, 'type' => DateTimeImmutable::class],
            [['deliver_from'], $this->validateDeliverTime(...)],
            [['courier_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['courier_id' => 'id'], 'skipOnError' => false],
            [['status'], 'default', 'value' => OrderStatusEnum::Draft],
            [['status'], TypeValidator::class, 'type' => OrderStatusEnum::class],
            [['feedback'], 'string'],
            [['feedback_assessment'], 'integer', 'min' => 1, 'max' => 5],
        ];
    }

    public function validateDeliverTime(): void
    {
        if ($this->deliver_to->getTimestamp() <= $this->deliver_from->getTimestamp()) {
            $this->addError('deliver_to', Yii::t('app', 'Время окончания доставки должно быть больше времени начала'));
        }
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_name' => Yii::t('app', 'Имя заказчика'),
            'customer_phone' => Yii::t('app', 'Телефон заказчика'),
            'address' => Yii::t('app', 'Адрес доставки'),
            'deliver_from' => Yii::t('app', 'Доставить с'),
            'deliver_to' => Yii::t('app', 'Доставить до'),
            'courier_id' => Yii::t('app', 'Курьер'),
            'created_at' => Yii::t('app', 'Создан'),
            'updated_at' => Yii::t('app', 'Обновлен'),
            'status' => Yii::t('app', 'Статус'),
            'delivered_at' => Yii::t('app', 'Доставлен'),
            'feedback' => Yii::t('app', 'Обратная связь'),
            'feedback_assessment' => Yii::t('app', 'Оценка'),
        ];
    }

    public function getFieldsEnum(): array
    {
        return [
            'status' => OrderStatusEnum::class,
        ];
    }

    public function getDateTimeFieldsType(): array
    {
        return [
            'created_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'updated_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'deliver_from' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'deliver_to' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'delivered_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
        ];
    }

    public function getCourier(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'courier_id']);
    }

    public function getCustomerPhoneHtml(): string
    {
        return "<a href=\"tel: $this->customer_phone\">$this->customer_phone</a>";
    }
}

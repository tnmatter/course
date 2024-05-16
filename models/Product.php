<?php

namespace app\models;

use app\db\AbstractPgModel;
use app\validators\TypeValidator;
use DateTimeImmutable;
use Yii;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $count
 * @property string|null $avatar
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 */
class Product extends AbstractPgModel
{
    public static function tableName(): string
    {
        return 'product';
    }
    
    public function rules(): array
    {
        return [
            [['name', 'count'], 'required'],
            [['name', 'avatar'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], TypeValidator::class, 'type' => DateTimeImmutable::class],
            [['count'], 'integer', 'min' => 0]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Товар'),
            'description' => Yii::t('app', 'Описание'),
            'count' => Yii::t('app', 'Количество'),
            'avatar' => Yii::t('app', 'Фото'),
            'created_at' => Yii::t('app', 'Создан'),
            'updated_at' => Yii::t('app', 'Обновлен'),
        ];
    }

    public function getDateTimeFieldsType(): array
    {
        return [
            'created_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'updated_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
        ];
    }
}

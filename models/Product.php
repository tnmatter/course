<?php

namespace app\models;

use app\db\AbstractPgModel;
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
            [['name'], 'string', 'max' => 255],
            [['description', 'avatar'], 'string'],
            [['avatar'], 'url'],
            [['count'], 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Название'),
            'description' => Yii::t('app', 'Описание'),
            'count' => Yii::t('app', 'Количество'),
            'avatar' => Yii::t('app', 'Картинка'),
            'created_at' => Yii::t('app', 'Создан'),
            'updated_at' => Yii::t('app', 'Обновлен'),
        ];
    }
}

<?php

namespace app\models;

use app\db\AbstractPgModel;
use borales\extensions\phoneInput\PhoneInputValidator;
use DateTimeImmutable;
use Yii;

/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 */
class User extends AbstractPgModel
{
    public static function tableName(): string
    {
        return 'user';
    }

    public function rules(): array
    {
        return [
            [['name', 'phone'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['phone'], PhoneInputValidator::class],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Имя'),
            'phone' => Yii::t('app', 'Телефон'),
            'created_at' => Yii::t('app', 'Создан'),
            'updated_at' => Yii::t('app', 'Обновлен'),
        ];
    }
}

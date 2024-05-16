<?php

namespace app\models;

use app\db\AbstractPgModel;
use app\helpers\HStrings;
use borales\extensions\phoneInput\PhoneInputValidator;
use DateTimeImmutable;
use Yii;
use yii\web\IdentityInterface;

/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $auth_key
 * @property string $password_hash
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 */
class User extends AbstractPgModel implements IdentityInterface
{
    public static function tableName(): string
    {
        return 'user';
    }

    public function rules(): array
    {
        return [
            [['name', 'phone', 'password_hash'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['phone'], PhoneInputValidator::class],
            [['phone'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Имя'),
            'phone' => Yii::t('app', 'Телефон'),
            'auth_key' => Yii::t('app', 'Ключ'),
            'password_hash' => Yii::t('app', 'Пароль'),
            'created_at' => Yii::t('app', 'Создан'),
            'updated_at' => Yii::t('app', 'Обновлен'),
        ];
    }

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = HStrings::randomString(64);
            }
            return true;
        }
        return false;
    }

    public static function findIdentity($id): User|null
    {
        return User::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): User|null
    {
        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): string|null
    {
        return null;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return password_verify(md5($password), $this->password_hash);
    }
}

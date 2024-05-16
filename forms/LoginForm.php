<?php

namespace app\forms;

use app\models\User;
use borales\extensions\phoneInput\PhoneInputValidator;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public string|null $phone = null;
    public string|null $password = null;

    public function rules(): array
    {
        return [
            [['phone', 'password'], 'required'],
            [['phone', 'password'], 'string'],
            [['phone'], PhoneInputValidator::class],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'phone' => Yii::t('app', 'Телефон'),
            'password' => Yii::t('app', 'Пароль'),
        ];
    }

    public function login(): bool
    {
        if ($this->validate()) {
            $user = User::findOne(['phone' => $this->phone]);
            if ($user) {
                if ($user->validatePassword($this->password)) {
                    Yii::$app->user->login($user);
                    return true;
                }
            }
            $this->addError('phone', Yii::t('app', 'Неверный телефон или пароль'));
        }
        return false;
    }
}

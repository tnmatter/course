<?php

namespace app\forms;

use app\models\User;
use borales\extensions\phoneInput\PhoneInputValidator;
use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public string|null $phone = null;
    public string|null $password = null;
    public string|null $repeat_password = null;
    public string|null $name = null;

    public function rules(): array
    {
        return [
            [['phone', 'password', 'repeat_password', 'name'], 'required'],
            [['phone', 'password', 'repeat_password'], 'string'],
            [['password'], $this->validatePassword(...)],
            [['repeat_password'], 'compare', 'compareAttribute' => 'repeat_password'],
            [['name'], 'string', 'max' => 255],
            [['phone'], PhoneInputValidator::class],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'phone' => Yii::t('app', 'Телефон'),
            'password' => Yii::t('app', 'Пароль'),
            'repeat_password' => Yii::t('app', 'Повторите пароль'),
            'name' => Yii::t('app', 'Имя'),
        ];
    }

    public function validatePassword(): void
    {
        $length = 8;
        if (mb_strlen($this->password) < $length) {
            $this->addError('password', Yii::t('app', "Пароль должен содержать не менее {cnt} символов", ['cnt' => $length]));
        }
        if (!preg_match("/[`!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?~]/", $this->password)
            || !preg_match('/\d/', $this->password)
            || !preg_match('/[a-zа-я]/u', $this->password)
            || !preg_match('/[A-ZА-Я]/u', $this->password)
        ) {
            $this->addError(
                'password',
                Yii::t(
                    'app',
                    "Пароль должен содержать буквы в разном регистре, а также хотя бы одну цифру и спецсимвол: !@#$%&*~()+=-.,.’"
                ),
            );
        }
    }

    public function signup(): bool
    {
        if ($this->validate()) {
            $user = new User([
                'name' => $this->name,
                'phone' => $this->phone,
                'password_hash' => password_hash(md5($this->password), PASSWORD_DEFAULT),
            ]);
            if ($user->save()) {
                Yii::$app->user->login($user);
                return true;
            } else {
                foreach (['name', 'phone'] as $attr) {
                    if ($user->hasErrors($attr)) {
                        $this->addError($attr, $user->getFirstError($attr));
                    }
                }
            }
        }
        return false;
    }
}

<?php

namespace app\validators;

use ArrayAccess;
use Closure;
use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\validators\RequiredValidator;
use yii\validators\Validator;

class ArrayValidator extends Validator
{
    /**
     * Необходимый формат валидируемого массива.
     * Содержит пары field => fieldFormat,
     * где fieldFormat это одно из следующих значений:
     * - строка с указанием функции для проверки
     *      (Ex. 'intval') (будет выполнена функция)
     * - функция обратного вызова в виде объекта первого класса или массива
     *      (Ex. $this->foo(...), [$this, 'foo']) (будет вызвана функция)
     * - массив, содержащий 1 значение - валидатор каждого проверяемого элемента в подмассиве
     *      (Ex. ['string'], [$this->foo(...)]) (валидатор будет применен к каждому элементу значения)
     * - строка с типом
     *      (Ex. 'integer', MyClass::class) (будет проверено совпадение типов)
     * - массив, содержащий валидаторы для поля, валидаторы будут применены с оператором OR
     * - массив, содержащий пары field => value, где value это одно из перечисленных выше значений или массив такого же формата
     *      (Ex. ['foo' => 'integer', 'bar' => $this->foo(...), 'baz' => ['foo' => MyClass::class]])
     * - валидатор
     *      (Ex. ['foo' => new NumberValidator(['min' => 0])])
     *
     * Перечисленные выше способы будут использованы в указанном порядке.
     */
    public array $format = [];
    /**
     * @var bool Скипать поля, если их нет в массиве и нет required валидатора
     */
    public bool $skipNotExistsField = false;
    public string|null $keyNotExistsMessage = null;
    public string|null $missingFieldsMessage = null;

    public function init(): void
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', 'Значение {value} не верно');
        }
        if ($this->keyNotExistsMessage === null) {
            $this->keyNotExistsMessage = Yii::t('yii', 'Неизвестный ключ {key}');
        }
        if ($this->missingFieldsMessage === null) {
            $this->missingFieldsMessage = Yii::t('yii', 'Отсутствуют необходимые поля');
        }
        $this->enableClientValidation = false;
        $this->whenClient = null;
    }

    /**
     * @param $value
     * @param $error
     *
     * @return bool
     * @throws NotSupportedException
     */
    public function validate($value, &$error = null): bool
    {
        $result = $this->validateValue($value);
        if (empty($result)) {
            return true;
        }

        [$message, $params] = $result;
        $params['attribute'] ??= Yii::t('yii', 'the input value');
        $error = $this->formatMessage($message, $params);
        return false;
    }

    /**
     * @param $model
     * @param $attribute
     *
     * @return void
     * @throws NotSupportedException
     */
    public function validateAttribute($model, $attribute): void
    {
        $result = $this->tryValidateValue($model->$attribute, $attribute, $model);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

    protected function validateValue($value): array|null
    {
        return $this->tryValidateValue($value);
    }

    /**
     * @param $value
     * @param string $attribute
     * @param Model|null $model
     *
     * @return array
     * @throws NotSupportedException
     */
    private function tryValidateValue($value, string $attribute = 'array', Model|null $model = null): array
    {
        if (!$this->valueIsArray($value)) {
            return [$this->message, []];
        }
        return $this->validateArrayByFormat($value, $this->format, [$attribute], $model);
    }

    private function valueIsArray(mixed $value): bool
    {
        return is_array($value) || ($value instanceof ArrayAccess);
    }

    /**
     * @param array|ArrayAccess $array
     * @param array $format
     * @param array $path
     * @param Model|null $model
     *
     * @return array
     * @throws NotSupportedException
     */
    private function validateArrayByFormat(array|ArrayAccess $array, array $format, array $path, Model|null $model = null): array
    {
        /**
         * Если формат является простым списком, то узнаем это
         */
        $formatIsList = array_is_list($format);
        foreach ($array as $key => $value) {
            $error = [];
            $path[] = $key;
            /**
             * Если формат -- простой список, то валидируем каждое значение первым валидатором из формата
             */
            if ($formatIsList) {
                $isAnd = false;
                if (is_string($format[0]) && strtolower($format[0]) === 'and') {
                    $isAnd = true;
                    $format = array_slice($format, 1);
                } elseif (is_string($format[0]) && strtolower($format[0]) === 'or') {
                    $format = array_slice($format, 1);
                }
                foreach ($format as $fmt) {
                    if ($this->valueIsArray($value)) {
                        $error = $this->validateArrayByFormat($value, $fmt, $path, $model);
                    } elseif (is_array($fmt) && array_is_list($fmt) || !is_array($fmt)) {
                        $error = $this->validateValueByFormat($value, $fmt, $path, $model);
                    } else {
                        array_pop($path);
                        $error = [$this->message, ['value' => $this->getPathString($path)]];
                    }
                    if (!empty($error)) {
                        if ($isAnd) {
                            break;
                        }
                    } else {
                        if (!$isAnd) {
                            break;
                        }
                    }
                }
            } else {
                /**
                 * Если значение присутствует в массиве, то валидируем его.
                 * Если значения нет -- возвращаем ошибку
                 */
                if (isset($format[$key])) {
                    if ($this->valueIsArray($value)) {
                        $error = $this->validateArrayByFormat($value, $format[$key], $path, $model);
                    } else {
                        $error = $this->validateValueByFormat($value, $format[$key], $path, $model);
                    }
                    unset($format[$key]);
                } else {
                    return [
                        $this->keyNotExistsMessage,
                        ['key' => $this->getPathString($path)],
                    ];
                }
            }
            /**
             * Если при валидации значения появилась ошибка, то возвращаем ее
             */
            if (!empty($error)) {
                return $error;
            }
            array_pop($path);
        }
        /**
         * Если формат не был списком и остались валидаторы, полей для которых нет в массиве, то возвращаем ошибку
         */
        if (!$formatIsList && $format) {
            if ($this->skipNotExistsField) {
                foreach ($format as $f) {
                    if (
                        ($f instanceof RequiredValidator)
                        || array_reduce($format, fn($r, $f) => $r || ($f instanceof RequiredValidator), false)
                    ) {
                        return [$this->missingFieldsMessage, []];
                    }
                }
            } else {
                return [$this->missingFieldsMessage, []];
            }
        }
        return [];
    }

    /**
     * @param mixed $value
     * @param array|string|Closure|Validator $format
     * @param array $path
     * @param Model|null $model
     *
     * @return array
     * @throws NotSupportedException
     */
    private function validateValueByFormat(
        mixed $value,
        array|string|Closure|Validator $format,
        array $path,
        Model|null $model = null
    ): array {
        if (is_callable($format)) {
            /**
             * Если формат -- колбек, просто вызываем его
             */
            $format($value);
        } elseif (is_string($format)) {
            $typeValidator = new TypeValidator(['type' => strtolower($format)]);
            $error = $typeValidator->validateValue($value);
            if ($error) {
                return [
                    $this->message,
                    ['value' => $this->getPathString($path)],
                ];
            }
        } elseif (is_array($format)) {
            $isAnd = false;
            if (is_string($format[0]) && strtolower($format[0]) === 'and') {
                $isAnd = true;
                $format = array_slice($format, 1);
            } elseif (is_string($format[0]) && strtolower($format[0]) === 'or') {
                $format = array_slice($format, 1);
            }
            $validated = $isAnd;
            foreach ($format as $fmt) {
                $error = $this->validateValueByFormat($value, $fmt, $path, $model);
                $validated = $isAnd ? ($validated && empty($error)) : ($validated || empty($error));
                if ($isAnd && !$validated || !$isAnd && $validated) {
                    break;
                }
            }
            if (!$validated) {
                return [
                    $this->message,
                    ['value' => $this->getPathString($path)],
                ];
            }
        } elseif ($format instanceof Validator) {
            $error = $format->validateValue($value);
            if (!empty($error)) {
                return [
                    $error[0],
                    array_merge(['attribute' => $this->getPathString($path)], $error[1]),
                ];
            }
        }
        return [];
    }

    private function getPathString(array $path): string
    {
        $attribute = array_shift($path);
        return "{$attribute}[" . implode('][', $path) . ']';
    }
}

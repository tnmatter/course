<?php

namespace app\widgets;

use app\assets\PasswordInputAsset;
use Yii;
use yii\bootstrap5\BaseHtml;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

class ActiveField extends \yii\bootstrap5\ActiveField
{
    public $options = ['class' => 'form-group'];

    public function hiddenInput($options = []): static
    {
        Html::addCssClass($this->options, 'd-none');
        return parent::hiddenInput($options);
    }

    public function telegramInput(array $options = []): static
    {
        $options = array_merge($this->inputOptions, $options);
        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::tag(
            'div',
            Html::tag('span', '@', ['class' => 'input-group-text', 'id' => 'addon-wrapping'])
            . Html::activeInput('text', $this->model, $this->attribute, $options),
            ['class' => 'input-group flex-nowrap'],
        );

        return $this;
    }

    public function select2Ajax(string $url, array $options = []): static
    {
        $options = array_merge(
            [
                'data' => [],
                'pluginOptions' => [
                    'ajax' => [
                        'url' => $url,
                        'minimumResultsForSearch' => -1,
                        'cache' => true,
                        'delay' => 500,
                        'data' => new JsExpression(
                            <<<JS
function (params) {
    return {
        query: params.term,
        page: params.page || 1
    };
}
JS,
                        ),
                    ],
                    'templateResult' => new JsExpression('function(data) {return data.html || data.text;}'),
                    'templateSelection' => new JsExpression('function(data) {return data.text;}'),
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                ],
            ],
            $options,
        );

        return $this->widget(Select2::class, $options);
    }

    public function passwordInput($options = []): static
    {
        $view = $this->form->getView();

        PasswordInputAsset::register($view);

        $id = BaseHtml::getInputId($this->model, $this->attribute);

        $pluginOptions = $options['pluginOptions'] ?? [];
        $pluginOptions['clientValidation'] = $pluginOptions['clientValidation'] ?? false;

        // Client validation (JS)
        if ($pluginOptions['clientValidation']) {
            $pluginOptions['messages']['validationCases'] = [
                'lowercase' => Yii::t('app', 'Строчную букву') . ' - abc',
                'uppercase' => Yii::t('app', 'Заглавную букву') . ' - ABC',
                 'symbol' => Yii::t('app', 'Спец. символ') . ' - @!?',
                'number' => Yii::t('app', 'Цифру'),
                'length' => Yii::t('app', '8 символов'),
            ];
            $pluginOptions['messages']['validationError'] = Yii::t('app', 'Должны быть соблюдены все правила');
            $pluginOptions['messages']['validationTip'] = Yii::t('app', 'Пароль должен содержать') . ':';
        }

        $pluginOptions = Json::encode($pluginOptions);

        if ($this->attribute != 'password') {
            $options['autocomplete'] = 'new-password';
        }

        $view->registerJs(
            <<<JS
            \$('#$id').password($pluginOptions);
        JS,
        );

        return parent::passwordInput($options);
    }
}

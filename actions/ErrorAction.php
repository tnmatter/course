<?php

namespace app\actions;

use app\models\User;
use Yii;

/**
 * @property User $user
 */
class ErrorAction extends \yii\web\ErrorAction
{
    public function renderAjaxResponse(): string
    {
        if (Yii::$app->getRequest()->headers->get('X-Is-Modal') === 'true') {
            return $this->renderHtmlResponse();
        }

        return $this->getExceptionName();
    }

    protected function getExceptionName(): string
    {
        return match ($this->getExceptionCode()) {
            403 => $this->getExceptionMessage() ?: Yii::t('app', 'Доступ запрещен'),
            404 => $this->getExceptionMessage() ?: Yii::t('app', 'Страница не найдена'),
            429 => $this->getExceptionMessage() ?: Yii::t('app', 'Слишком много запросов'),
            500 => Yii::t('app', 'Произошла ошибка') . ': ' . $this->getExceptionMessage(),
            default => parent::getExceptionName(),
        };
    }
}

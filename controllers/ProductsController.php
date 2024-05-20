<?php

namespace app\controllers;

use app\enum\BootstrapColorClassEnum;
use app\filters\ProductsFilter;
use app\models\Product;
use app\responses\models\select2\Select2Pagination;
use app\responses\models\select2\Select2Response;
use app\responses\models\select2\Select2ResponseModel;
use Yii;
use yii\bootstrap5\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProductsController extends AbstractController
{
    public function actionIndex(): string
    {
         $filter = new ProductsFilter();
         return $this->render('index', compact('filter'));
    }

    public function actionCreate(): Response|string
    {
        $product = new Product();
        if ($product->load($this->request->post())) {
            $this->performAjaxValidation($product);
            if ($product->save()) {
                $this->setSaveFlash();
                return $this->redirect('/products');
            } else {
                $this->setValidationFlash();
            }
        }
        return $this->render('create', compact('product'));
    }

    public function actionUpdate(int $id): Response|string
    {
        $product = Product::findOne($id);
        if ($product !== null) {
            if ($product->load($this->request->post())) {
                $this->performAjaxValidation($product);
                if ($product->save()) {
                    $this->setSaveFlash();
                    return $this->redirect('/products');
                } else {
                    $this->setValidationFlash();
                }
            }
            return $this->render('update', compact('product'));
        }
        throw new NotFoundHttpException();
    }

    public function actionFilter(string|null $query = null, int|null $id = null): Response
    {
        $filter = new ProductsFilter(['search' => $query, 'id' => $id]);
        $provider = $filter->filterSearch();
        $models = $provider->getModels();
        $response = new Select2Response(
            array_map(
                fn(Product $p) => new Select2ResponseModel(
                    id: $p->id,
                    text: "$p->name ($p->count)",
                    html: Html::tag(
                        'div',
                        ($p->avatar ? "<img src='$p->avatar' alt='' class='select2-img'>" : '')
                        . $p->name
                        . ' ('
                        . (match (true) {
                            $p->count === 0 => Html::tag(
                                'span',
                                Yii::t('app', 'нет в наличии!'),
                                ['class' => BootstrapColorClassEnum::Danger->getTextClass()],
                            ),
                            $p->count < 10 => Html::tag(
                                'span',
                                Yii::t(
                                    'app',
                                    '{c, plural, one{осталaсь} other{осталось}} всего {c, plural, one{# штука} many{# штуки} other{# штук}}',
                                    ['c' => $p->count],
                                ),
                                ['class' => BootstrapColorClassEnum::Warning->getTextClass()],
                            ),
                            default => Html::tag(
                                'span',
                                Yii::t(
                                    'app',
                                    '{c, plural, one{осталась # штука} many{осталось# штуки} other{осталось # штук}}',
                                    ['c' => $p->count],
                                ),
                                ['class' => BootstrapColorClassEnum::Secondary->getTextClass()],
                            )
                        })
                        . ')',
                        ['class' => 'd-flex align-items-center'],
                    ),
                ),
                $models,
            ),
            Select2Pagination::getInstanceByDataProvider($provider->pagination),
        );
        return $this->asJson($response);
    }
}
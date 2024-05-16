<?php

namespace app\controllers;

use app\controllers\AbstractController;
use app\models\Product;
use app\filters\ProductsFilter;
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
}
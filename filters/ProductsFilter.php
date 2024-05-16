<?php

namespace app\filters;

use app\data\Sort;
use app\models\Product;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProductsFilter extends Model
{
    public function search(): ActiveDataProvider
    {
        $query = Product::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'attributes' => ['id', 'name'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
        return $dataProvider;
    }
}
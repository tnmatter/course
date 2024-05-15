<?php

namespace app\filters;

use app\data\Sort;
use app\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrdersFilter extends Model
{
    public function search(): ActiveDataProvider
    {
        $query = Order::find()
            ->with(['courier']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
        return $dataProvider;
    }
}

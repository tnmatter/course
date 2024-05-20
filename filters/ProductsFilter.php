<?php

namespace app\filters;

use app\data\Sort;
use app\models\Product;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class ProductsFilter extends Model
{
    public int|null $id = null;
    public string|null $search = null;

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

    public function filterSearch(): ActiveDataProvider
    {
        $query = Product::find()
            ->alias('t')
            ->filterWhere(['t.id' => $this->id])
            ->andFilterWhere([
                'OR',
                ['ILIKE', 't.name', $this->search],
                ['ILIKE', 't.description', $this->search],
            ]);
        if ($this->search !== null) {
            $query->orderBy([
                new Expression("strpos(t.name, 'name') != 0 DESC"),
                new Expression("strpos(t.name, 'name') ASC"),
                new Expression("strpos(t.description, 'name') ASC"),
            ]);
        }
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'attributes' => ['id', 'name'],
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);
    }
}
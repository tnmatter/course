<?php

use app\models\Order;
use yii\bootstrap5\Html;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use app\models\Product;
use app\models\Order as OrderAlias;

?>
<div class="row">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                    <?php
                    echo Highcharts::widget([
                        'scripts' => [
                            'modules/exporting',
                            'themes/grid-light',
                        ],
                        'options' => [
                            'title' => [
                                'text' => 'Количество товара',
                            ],
                            'labels' => [
                                'items' => [
                                    [
                                        'html' => '',
                                        'style' => [
                                            'left' => '50px',
                                            'top' => '18px',
                                            'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                                        ],
                                    ],
                                ],
                            ],
                            'series' => [
                                [
                                    'type' => 'pie',
                                    'name' => 'Количество',
                                    'data' => [
                                        [
                                            'name' => 'Заканчивающиеся товары',
                                            'y' => Product::find()
                                            ->where('count' < 10)
                                            ->count(),
                                            'color' => new JsExpression('Highcharts.getOptions().colors[0]'), // Jane's color
                                        ],
                                        [
                                            'name' => 'Товары в достатке',
                                            'y' => 5,
                                            'color' => new JsExpression('Highcharts.getOptions().colors[1]'), // John's color
                                        ],
                                    ],
                                    'center' => [320, 150],
                                    'size' => 250,
                                    'showInLegend' => false,
                                    'dataLabels' => [
                                        'enabled' => false,
                                    ],
                                ],
                            ],
                        ]
                    ]);
                    ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <?php
                echo Highcharts::widget([
                    'scripts' => [
                        'modules/exporting',
                        'themes/grid-light',
                    ],
                    'options' => [
                        'title' => [
                            'text' => 'Combination chart',
                        ],
                        'labels' => [
                            'items' => [
                                [
                                    'html' => 'Текущее',
                                    'style' => [
                                        'left' => '50px',
                                        'top' => '18px',
                                        'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                                    ],
                                ],
                            ],
                        ],
                        'series' => [
                            [
                                'type' => 'pie',
                                'name' => 'Total count of products',
                                'data' => [
                                    [
                                        'name' => 'Jane',
                                        'y' => 13,
                                        'color' => new JsExpression('Highcharts.getOptions().colors[0]'), // Jane's color
                                    ],
                                    [
                                        'name' => 'John',
                                        'y' => 23,
                                        'color' => new JsExpression('Highcharts.getOptions().colors[1]'), // John's color
                                    ],
                                    [
                                        'name' => 'Joe',
                                        'y' => 22,
                                        'color' => new JsExpression('Highcharts.getOptions().colors[2]'), // Joe's color
                                    ],
                                ],
                                'center' => [300, 150],
                                'size' => 200,
                                'showInLegend' => false,
                                'dataLabels' => [
                                    'enabled' => false,
                                ],
                            ],
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <?php
                echo Highcharts::widget([
                    'scripts' => [
                        'modules/exporting',
                        'themes/grid-light',
                    ],
                    'options' => [
                        'title' => [
                            'text' => 'Combination chart',
                        ],
                        'labels' => [
                            'items' => [
                                [
                                    'html' => 'Текущее',
                                    'style' => [
                                        'left' => '50px',
                                        'top' => '18px',
                                        'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                                    ],
                                ],
                            ],
                        ],
                        'series' => [
                            [
                                'type' => 'pie',
                                'name' => 'Total count of products',
                                'data' => [
                                    [
                                        'name' => 'Jane',
                                        'y' => 13,
                                        'color' => new JsExpression('Highcharts.getOptions().colors[0]'), // Jane's color
                                    ],
                                    [
                                        'name' => 'John',
                                        'y' => 23,
                                        'color' => new JsExpression('Highcharts.getOptions().colors[1]'), // John's color
                                    ],
                                    [
                                        'name' => 'Joe',
                                        'y' => 22,
                                        'color' => new JsExpression('Highcharts.getOptions().colors[2]'), // Joe's color
                                    ],
                                ],
                                'center' => [300, 150],
                                'size' => 200,
                                'showInLegend' => false,
                                'dataLabels' => [
                                    'enabled' => false,
                                ],
                            ],
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <?php
                echo Highcharts::widget([
                    'scripts' => [
                        'modules/exporting',
                        'themes/grid-light',
                    ],
                    'options' => [
                        'title' => [
                            'text' => 'Combination chart',
                        ],
                        'labels' => [
                            'items' => [
                                [
                                    'html' => 'Текущее',
                                    'style' => [
                                        'left' => '50px',
                                        'top' => '18px',
                                        'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                                    ],
                                ],
                            ],
                        ],
                        'series' => [
                            [
                                'type' => 'pie',
                                'name' => 'Total count of products',
                                'data' => [
                                    [
                                        'name' => 'Jane',
                                        'y' => 13,
                                        'color' => new JsExpression('Highcharts.getOptions().colors[0]'), // Jane's color
                                    ],
                                    [
                                        'name' => 'John',
                                        'y' => 23,
                                        'color' => new JsExpression('Highcharts.getOptions().colors[1]'), // John's color
                                    ],
                                    [
                                        'name' => 'Joe',
                                        'y' => 22,
                                        'color' => new JsExpression('Highcharts.getOptions().colors[2]'), // Joe's color
                                    ],
                                ],
                                'center' => [300, 150],
                                'size' => 200,
                                'showInLegend' => false,
                                'dataLabels' => [
                                    'enabled' => false,
                                ],
                            ],
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
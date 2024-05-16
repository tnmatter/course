<?php

namespace app\assets;

use yii\web\AssetBundle;

class PasswordInputAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/password.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}

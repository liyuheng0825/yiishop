<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    //>>静态文件所在路径
    public $basePath = '@webroot';
    //>>静态文件所在URL
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    //>>加载js
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}

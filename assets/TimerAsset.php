<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TimerAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';
    //public $baseUrl = '@web';
    public $css = [
        'scss/opmaak.scss',
        'scss/jquery-ui.min.css',
    ];
    public $js = [
        'js/script.js',
        'js/user.js',
        'js/countdown-timer.js',
        'js/jquery-ui.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
        //'yii\jui\JuiAsset',
    ];
}


//previous, original version of this class:
/* class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
} */
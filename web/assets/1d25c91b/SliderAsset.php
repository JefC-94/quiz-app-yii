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
class SliderAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';
    public $css = [
        'scss/opmaak.scss',
        'scss/video.popup.css',
        'scss/flexslider.css',
        'scss/lightbox.min.css',
        'scss/jquery-ui.min.css',
    ];
    public $js = [
        'js/script.js',
        'js/user.js',
        'js/video.popup.js',
        'js/jquery.flexslider-min.js',
        'js/lightbox.min.js',
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
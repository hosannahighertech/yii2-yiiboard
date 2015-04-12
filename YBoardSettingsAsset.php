<?php

namespace app\modules\yboard;
 

class YBoardSettingsAsset extends \yii\web\AssetBundle 
{
    public $sourcePath = '@yboard/assets'; 
   /* public $css = [
        'css/base/forum.css',
    ]; */
    public $js = [
        'js/yboard.js',
        'js/ybsetting.js',
    ];
    
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
        
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}

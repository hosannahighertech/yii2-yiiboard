<?php

namespace app\modules\yboard;
 

class YBoardAsset extends \yii\web\AssetBundle 
{
    public $sourcePath = '@yboard/assets'; 
    public $css = [];
    public $js = [
        'js/yboard.js',
    ];
    
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
        
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset', 
        'app\assets\HighlighterAsset',
    ]; 
}

<?php

namespace app\modules\yboard\widgets\picker;

use yii\web\AssetBundle;

class ColorPickerAsset extends AssetBundle
{
    public $sourcePath = '@picker/assets';  
    
    public $js = [
        'js/spectrum.js',
    ];
    
    public $css = [];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

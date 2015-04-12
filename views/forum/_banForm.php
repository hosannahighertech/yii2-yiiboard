<?php

use yii\helpers\Html;
use app\modules\yboard\YBoard; 

/* @var $this yii\web\View */

//for datepicker
$this->registerJs('
    $( "#ban-expires" ).datepicker({dateFormat:"yy-mm-dd"});
'); 
?>

<div class="yboard-ban-form">

    <?= Html::beginForm() ?>
    <div class="form-group">
        <div class="form-label pad5 header3"> 
            <?= YBoard::t('yboard', 'Ban Reason') ?>
        </div>
        <?= Html::textArea('message', null, ['id'=>'ban-message', 'maxlength' => 255, 'class'=>'form-control']) ?>
    </div>
    
    <div class="form-group">
        <div class="form-label pad5 header3"> 
            <?= YBoard::t('yboard', 'Ban Expiry Date ({format})', ['format'=>'YYYY-MM-DD']) ?>
        </div>
        <?= Html::textInput('expires', null, ['id'=>'ban-expires', 'maxlength' => 255,  'class'=>'form-control']) ?> 
    </div>

    <?= Html::endForm(); ?>

</div>


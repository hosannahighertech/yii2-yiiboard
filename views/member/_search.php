<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\yboard\YBoard;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardMemberSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="yboard-member-search">
    <div class="row">
        <div class="col-md-8" >  
        </div>
        
        <div class="col-md-4">        
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]); ?>            
                
                <?= $form->field($model, 'username')->label("")->hint(YBoard::t('yboard', 'search username')) ?>     
            
            <?php ActiveForm::end(); ?>
        </div>
    </div> 
</div>

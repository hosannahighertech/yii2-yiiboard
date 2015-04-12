<?php
/* @var $this ForumController */
/* @var $poll YBoardPoll */
/* @var $choices array */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="form"> 
     <?php $form = ActiveForm::begin([
        'id'=>'edit-poll-form',
        'action'=>array('forum/update-poll','id'=>$poll->id),
        'enableAjaxValidation'=>false,
    ]) ?>

    <div class="form-group">
        <?php echo Html::activeLabel($poll,'question'); ?>
        <?php echo Html::activeTextInput($poll,'question',['maxlength'=>255, 'class'=>'form-control']); ?>
    </div>

    <?php echo Html::errorSummary($poll); ?>

    <div class="form-group" id="poll-choices">
        <?php echo Html::label(YBoard::t('yboard','Choices'),false, ['class'=>'form-label']); ?>
        <?php foreach($choices as $key => $value): ?>
            <div class="pad5-top">
                <?php echo Html::textInput('choice['.$key.']',$value,[
                    'maxlength'=>80, 
                    'class'=>'form-control',
                    'id'=>$key+1,
                    'onchange'=>'pollChange(this)',
                ]); ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="form-group">
        <?php echo Html::activeCheckbox($poll,'allow_revote'); ?> &nbsp;
        <?php echo Html::activeCheckbox($poll,'allow_multiple'); ?> &nbsp;
        <?php echo Html::activeHiddenInput($poll,'expire_date', ['id'=>'expire_date']); ?>    
        <strong>
            <?php echo YBoard::t('yboard','Poll expires'); ?>:
        </strong>

        
        <?= \yii\jui\DatePicker::widget([
            'name' => 'expiredate',  
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'defaultDate' => $poll->expire_date,
                'showAnim'=>'fold',
                'onSelect' =>new \yii\web\JsExpression('function(date, picker){
                    $("#expire_date").val(date);
                }')
            ], 
        ]) ?> 
    </div>
    <div class="form-group">
           <?php echo Html::submitButton(YBoard::t('yboard','Save'),['class'=>'btn btn-primary btn-md']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div><!-- form -->

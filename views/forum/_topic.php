<?php
/* @var $this ForumController */
/* @var $model YBoardTopic */
use \yii\helpers\Html;
use app\modules\yboard\YBoard;
use app\modules\yboard\components\DateTimeCalculation;
?>

<div class="row topic visible-xs"> 
    <div class="col-xs-2 forum-cell <?php echo $this->context->topicIcon($model); ?>"> </div>
    
    <div class="col-xs-10 forum-cell main"> 
        <div class="header3">
            <?php echo Html::a(Html::encode($model->title).' '.($model->approved==0? YBoard::t('yboard', '[Pending]') : ''), ['topic', 'id'=>$model->id], ['class'=>$model->hasPostedClass()]); ?>
        </div>
        <div>
            <?= YBoard::t('yboard', '{replies, plural, =0{No Reply} =1{One Reply} other{# Replies}}. Last: {user} {time}', ['replies'=>$model->num_replies, 'user'=>Html::encode($model->lastPost->poster->profile->username), 'time'=>DateTimeCalculation::short($model->lastPost->create_time)]) ?>
        </div>
    </div>
</div>

<div class="row topic hidden-xs">  
    <div class="col-md-1 forum-cell <?php echo $this->context->topicIcon($model); ?>">
    </div>
    
    <div class="forum-cell main col-md-4">
        <div class="row">
            <div class="col-md-12 header2">
                <?php echo Html::a(Html::encode($model->title).' '.($model->approved==0? YBoard::t('yboard', '[Pending]') : ''), ['topic', 'id'=>$model->id], ['class'=>$model->hasPostedClass()]); ?>
                
                <?php if(Yii::$app->user->can('moderator')): ?>
                    <?php echo Html::img($this->context->module->getRegisteredImage('empty.png'), ['alt'=>'empty']); ?>
                    <?php echo Html::img($this->context->module->getRegisteredImage('update.png'), ['update', 'title'=>YBoard::t('yboard', 'Update topic'), 'style'=>'cursor:pointer', 'onclick'=>'YBoard.updateTopic(' . $model->id . ', "' . \Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/moderator/topic') . '")']); ?>
                <?php endif; ?>
            </div> 
            
            <div class="col-md-12 ">
                <?php echo YBoard::t('yboard', 'started by ') . Html::encode($model->starter->profile->username);?>
                <?php echo ' ' . YBoard::t('yboard', 'on') . ' ' . DateTimeCalculation::medium($model->firstPost->create_time); ?>
            </div>
        </div>
    </div>
    
    <div class="forum-cell center col-md-2 ">
        <?php echo Html::encode($model->num_replies); ?><br>
        <?php echo Html::encode($model->getAttributeLabel('num_replies')); ?>
    </div>
    
    <div class="forum-cell center col-md-2 ">
        <?php echo Html::encode($model->num_views); ?><br>
        <?php echo Html::encode($model->getAttributeLabel('num_views')); ?>
    </div>
    
    <div class="forum-cell last-cell col-md-3">
        <?php 
            echo Html::encode($model->lastPost->poster->profile->username);
            echo Html::a(Html::img($this->context->module->getRegisteredImage('next.png'), ['alt'=>'next', 'style'=>'margin-left:5px;']), ['topic', 'id'=>$model->id, 'nav'=>'last'], ['title'=>YBoard::t('yboard', 'Last Reply')]);
            echo '<br>';
            echo DateTimeCalculation::longDate($model->lastPost->create_time);
        ?>
    </div>
</div>

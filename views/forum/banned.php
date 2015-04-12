<?php
/* @var $this ForumController */
/* @var $error Exception */

use yii\helpers\Html;
use app\modules\yboard\YBoard;
use app\modules\yboard\components\DateTimeCalculation;


$this->title = YBoard::t('yboard', 'You are Banned!');
 
$this->params['breadcrumbs'] = [
	['label'=>YBoard::t('yboard', 'Forum'), 'url'=>['forum/index']],
    YBoard::t('yboard', 'Banned Member')
];  
 
?>

<div id="yboard-wrapper" class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="header2 pad5"><?= YBoard::t('yboard', 'You\'ve been Banned ') ?></p>
        </div>

        <div class=" col-md-6 alert alert-warning panel " style="display:inline-block;">
            <p><?php echo nl2br(YBoard::t('yboard', 'Dear {username}, you have been banned. 
                If You think its a mistake please consider reporting to use at [{email}]. 
                You can send an appeal there stating your reasons why ban should be lifted up. 
                Below are ban details. 
                Thank you,
                Management
            ', 
            [
                'username'=>$member->profile->username, 
                'email'=>$email,
                'br'=>'\n'
            ]
            )); ?></p>
            
            
        </div>
        
        <div class="col-md-1 hidden-xs"></div>
        
        <div class="col-md-5 alert alert-danger" style="display:inline-block;">
            <span class="header3"><?= YBoard::t('yboard', 'Ban Details') ?></span><br>
            <p><?= YBoard::t('yboard', 'Banned on: {time}', ['time'=>DateTimeCalculation::long($model->banned_on)]) ?></p>
            <p><?= YBoard::t('yboard', 'Ban Type: {type}', ['type'=>$isIp ? YBoard::t('yboard', 'IP Ban') : YBoard::t('yboard', 'Member Ban')]) ?></p>
            <p><?= YBoard::t('yboard', 'Reason: {reason}', ['reason'=>$model->message]) ?></p>                
            <p><?= YBoard::t('yboard', 'Ban to Lift on: {time}', ['time'=>DateTimeCalculation::long($model->expires)]) ?></p>                
            <p><?= YBoard::t('yboard', 'Ban length: {days} days {hours} hours', DateTimeCalculation::getDiff($model->banned_on, $model->expires)) ?></p>                
            <p><?= YBoard::t('yboard', 'Remained: {days} days {hours} hours', DateTimeCalculation::getDiff(time(), $model->expires)) ?></p>                
         </div>
    </div>	
</div>


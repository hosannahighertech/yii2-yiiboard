<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\yboard\YBoard;
?>

<div class="container">
    <div class="row single-member">
        <div class="col-md-2">
           <div class="center">
               <?= Html::img(isset($model->profile->image)? $model->profile->image:$this->context->module->getRegisteredImage("empty.jpeg"),['id'=>'user-avatar'])  ?>
            </div>
        </div>
        
        <div class="col-md-10">
            <div class="header2">
                <?= Html::a(ucwords($model->profile->{$this->context->module->userNameColumn}), ['member/view', 'id'=>$model->id]) ?>
            </div>
            
            <div class="user-info">
                <?php
                    $gender = $this->context->module->genderColumn;
                    $regdate = $this->context->module->regdateColumn; 
                    
                    $timezone = isset($model->profile->timezone)? new DateTimeZone($model->profile->timezone) : new DateTimeZone() ;
                ?>
                                
                <?= DetailView::widget([
                    'model' => $model,
                    'options'=>[
                        'class' => 'table table-striped detail-view'
                    ],
                    'attributes' => [ 
                        [
                            'label'=>YBoard::t('yboard', 'Last Visit'),
                            'value'=>date_format(date_timezone_set(date_create($model->last_visit), $timezone), 'l, d-M-y H:i T'),
                        ],    
                        [
                            'label'=>YBoard::t('yboard', 'Gender'),
                            'value'=>($model->profile->{$gender}==1?YBoard::t('yboard', 'Male'):YBoard::t('yboard', 'Female'))                        ],    
                        [
                            'label'=>YBoard::t('yboard', 'Joined'),
                            'value'=>$regdate==null?YBoard::t('yboard', 'None'): date_format(date_timezone_set(date_timestamp_set(date_create(), $model->profile->{$regdate}), $timezone), 'l, d-M-y H:i T'),
                        ],    
                        [
                            'label'=>YBoard::t('yboard', 'Group'),
                            'value'=> isset($model->group)? Html::encode($model->group->name) : Yiit('app', 'Member')
,
                        ], 
                        'status',
                    ],
                ]) ?>    
            </div>
        </div>
    </div>
</div>  

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\yboard\YBoard;
use yii\bootstrap\ButtonDropdown;
?>

<div class="container">
    <div class="row single-member">
        <div class="col-md-2">
           <div class="center">
                <?= Html::img(isset($model->profile->image)? $model->profile->image:$this->context->module->getRegisteredImage("empty.jpeg"),['id'=>'user-avatar'])  ?>
            </div> 
            
            <div class="center pad5-top">
                <?= ButtonDropdown::widget([
                    'label' => YBoard::t('yboard', 'Options'),
                    'dropdown' => [
                        'items' => [
                            ['label' => 'View Profile', 'url' => ['member/view', 'id'=>$model->id]], 
                            
                            [
                                'label' => YBoard::t('yboard','Ban user'), 
                                'url' => '',
                                'options'=>[
                                    //'class'=>'btn btn-danger btn-sm',
                                ],
                                'linkOptions'=>[
                                'title'=>YBoard::t('yboard', 'Ban this user'),
                                    'onclick'=>'if(confirm("' . YBoard::t('yboard','Do you really want to Ban this User?') . '")) { banUser('.Yii::$app->user->id.', "' . Yii::$app->urlManager->createAbsoluteUrl(['/'.$this->context->module->id.'/moderator/ban-user', 'id'=>$model->id]) . '");  }return false;',
                                ],
                            ],
                            
                            ['label' => YBoard::t('yboard','Edit Profile'), 'url' => ['edit-profile', 'id'=>$model->id]], 

                            [
                                'label' => YBoard::t('yboard','Delete user'), 
                                'url' => '',
                                'options'=>[
                                    //'class'=>'btn btn-danger btn-sm',
                                ],
                                'linkOptions'=>[
                                'title'=>YBoard::t('yboard', 'Ban this user'),
                                    'onclick'=>'if(confirm("' . YBoard::t('yboard','Delete this User (cannot be undone)?') . '")) { deleteMember("'. Yii::$app->urlManager->createAbsoluteUrl(['/'.$this->context->module->id.'/setting/delete-user', 'id'=>$model->id]) . '");  }return false;',
                                ],
                            ], 
                        ],
                    ],
                ]) ?>
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

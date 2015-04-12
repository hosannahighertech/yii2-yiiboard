<?php
/* @var $this ForumController */
/* @var $model YBoardPost */
/* @var $postId integer */

use app\modules\yboard\YBoard;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm; 
use yii\web\JsExpression; 
use yii\jui\Dialog;
use yii\widgets\ListView;
use app\modules\yboard\components\DateTimeCalculation;
use kartik\widgets\StarRating;
use yii\bootstrap\ButtonDropdown;
?> 
<div id="<?= $model->id ?>">  
    <?php if($this->context->poll !== null && $this->context->poll->post_id == $model->id): ?>
		<div class="yboard-poll">
			<strong><?php echo YBoard::t('yboard', 'Poll') . ': ' .$this->context->poll->question; ?></strong>
			<div id="poll">
                <?php if($this->context->voted): ?>
                    
                    <?= ListView::widget([ 
                        'id'=>'yboardPoll',
                        'itemView'=>'_pollResult', 
                        'dataProvider'=>$dataProvider, 
                        'summary'=>false,
                    ]); 
                    
                    
                    echo '<div>';
                    if($this->context->poll->user_id == Yii::$app->user->id || Yii::$app->user->can('moderator')) {
                        echo Html::button(YBoard::t('yboard', 'Edit poll'), array('class'=>'btn btn-default btn-sm','onclick'=>'editPoll(' . $this->context->poll->id . ', "' . Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/forum/edit-poll') . '");'))."&nbsp;";
                    }
                    if(!Yii::$app->user->isGuest && $this->context->poll->allow_revote && (!isset($this->context->poll->expire_date) || $this->context->poll->expire_date > date('Y-m-d'))) {
                        echo " ".Html::button(YBoard::t('yboard', 'Change vote'), array('class'=>'btn btn-default btn-sm','onclick'=>'changeVote(' . $this->context->poll->id . ', "' . Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/forum/display-vote') . '");'));
                    }
                    echo '</div>';
                    ?>
                <?php else: ?>
                    <?php echo Html::beginForm('', 'post', array('id'=>'yboard-poll-form'));
                    echo Html::hiddenInput('poll_id', $this->context->poll->id); 
                                    
                    echo ListView::widget([ 
                        'id'=>'yboardPoll',
                        'itemView'=>'_pollChoice', 
                        'dataProvider'=>$this->context->choiceProvider, 
                        'summary'=>false,
                    ]); 
                    
                    echo '<div>';
                    echo Html::button(YBoard::t('yboard', 'Vote'), [
                        'onclick'=>'vote("' . Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/forum/vote') . '");',
                        'class'=>'btn btn-default btn-sm',
                    ]);
                    echo '</div>';
                    echo Html::endForm(); ?>
                <?php endif; ?>
			</div>
		</div>
    <?php endif; ?>
    
    <?php if($index==0): ?>  
        <div> 
            <div class="header2 forum-category " >
                <p class="pad5"><?= $topic->approved == 0 ? $topic->title.' '.YBoard::t('yboard', '[Pending]') :  $topic->title ?></p>
            </div> 
        </div> 
    <?php endif; ?>
        
    <div class="row hidden-xs">
        <div class="col-md-4 member-cell" >            
            <div class="avatar img-responsive pull-left">
                <?php echo Html::img((isset($model->poster->profile->image))?$model->poster->profile->image:$this->context->module->getRegisteredImage('empty.jpeg'), ['alt'=>'avatar']); ?>
                <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->id!=$model->poster->id): ?>
                    <div>
                        <?= Html::button(YBoard::t('yboard', 'Send PM').' '.Html::img($this->context->module->getRegisteredImage('pm.png'),  ['alt'=>'', 'style'=>'border:none;']), ['title'=>YBoard::t('yboard', 'Send private message'), 'style'=>'cursor:pointer; margin:5px;', 'class'=>'btn btn-info btn-sm', 'onclick'=>'sendPm('.$model->poster->id.');']) ?> 
                    </div>
                <?php endif; ?>
            </div>  
              
            <div class="pull-left">
                <div class="col-md-12 membername header2">
                    <?php echo Html::a(Html::encode($model->poster->profile->username), ['member/view', 'id'=>$model->poster->id], ['class'=>'header2']); ?>
                </div>      
                
                <div id="online"> 
                    <?= strcasecmp($model->poster->status, 'hidden')==0 ? '' : 
                        (Html::img($this->context->module->getRegisteredImage($model->poster->status==YBoard::t('yboard','Online')?'buddy_online.gif':'buddy_offline.gif'), ['alt'=>'Online', 'title'=>'Use is online']).' '.$model->poster->status)
                    ?>                    
                </div>
                
                <!-- Group -->
                <div class="group pad15-left">
                    <?= Html::img($this->context->module->getRegisteredImage('groups/'.$model->poster->group->image.'?t='.time()), ['alt'=>'', 'title'=>$model->poster->group->description, 'class'=>'pull-right']) ?>
                </div>   
               
               <!-- Rank -->
                <?php if($model->poster->rank!==null): ?> 
                    <div class="no-padding">
                        <?= $model->poster->isBanned() ? YBoard::t('yboard', 'Banned') :                          
                         Html::img($this->context->module->getRegisteredImage('ranks/'.$model->poster->rank->stars.'.png?t='.time()), ['alt'=>'', 'title'=>$model->poster->rank->title, 'class'=>'']) ?>
                    </div>  
                <?php endif; ?> 
                
                <div class='navbar-inverse1' style="margin:5px 0 10px 15px; text-align:left;">
                    <?php if($model->poster->twitter!=''): ?>
                        <?= Html::a(Html::img($this->context->module->getSocialImage('twitter.png'), ['class'=>'round-grey', 'title'=>YBoard::t('yboard', 'Twitter'), 'style'=>'border:0; height:24px;']), strncmp($model->poster->twitter, 'http', 4)==0 ? $model->poster->twitter : "http://twitter.com/{$model->poster->twitter}") ?> 
                    <?php endif; ?>
                    
                    <?php if($model->poster->skype!=''): ?>
                        <?= Html::a(Html::img($this->context->module->getSocialImage('skype.png'), ['class'=>'round-grey', 'title'=>YBoard::t('yboard', 'Login to Skype and click me!'), 'style'=>'border:0; height:24px;']), "skype:{$model->poster->skype}?userinfo") ?> 
                    <?php endif; ?>
                    
                    <?php if($model->poster->github!=''): ?>
                        <?= Html::a(Html::img($this->context->module->getSocialImage('github.png'), ['class'=>'round-grey', 'title'=>YBoard::t('yboard', 'Twitter'), 'style'=>'border:0; height:24px;']), strncmp($model->poster->github, 'http', 4)==0 ? $model->poster->github : "http://github.com/{$model->poster->github}") ?> 
                    <?php endif; ?>
                    
                    <?php if($model->poster->linkedin!=''): ?>
                        <?= Html::a(Html::img($this->context->module->getSocialImage('linkedin.png'), ['class'=>'round-grey', 'title'=>YBoard::t('yboard', 'LinkedIn'), 'style'=>'border:0; height:24px;']), $model->poster->linkedin) ?> 
                    <?php endif; ?>
                </div>
            </div> 
        </div>  

        <div class="col-md-8 pull-right">
            <div class="user-info pull-right">
                <div class="memberinfo">
                    <?= YBoard::t('yboard', 'Topics') . ': ' . Html::encode($model->poster->startedTopics) ?><br>
                    <?= YBoard::t('yboard', 'Replied') . ': ' . Html::encode($model->poster->totalReplies) ?><br>
                    <?= YBoard::t('yboard', 'Joined') . ': ' . DateTimeCalculation::shortDate($model->poster->first_visit) ?><br>
                    <?= YBoard::t('yboard', 'Appreciation{plural} : {appr} ', ['appr'=>$model->poster->appreciations>0 ? $model->poster->appreciations : YBoard::t('yboard', 'No'), 'plural'=>$model->poster->appreciations>1?'s':''])  ?>
                    <?php if($model->poster->location!=''): ?>
                        <div class="location">
                            <?= YBoard::t('yboard', 'Location: {loc}', ['loc'=>Html::encode($model->poster->location)]) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>  
    </div>
    
    <div class= "post-separator"> </div>
    
    <div class= "post-time hidden-xs" > 
        <?= YBoard::t('yboard', 'Posted on {time}', ['time'=>DateTimeCalculation::medium($model->create_time)]) ?>
        <?= Html::a(YBoard::t('yboard', '{pl} (permalink)', ['pl'=>'#'.($index+1)]), Url::to(['forum/topic', 'id' => $model->topic_id, '#' => $model->id]), ['class'=>'pull-right']) ?>
    </div>
    
    <div class="row post-time visible-xs">
        <div class="col-xs-3"> 
            <div class="avatar-mobile img-responsive pull-left">
                <?php echo Html::img((isset($model->poster->profile->image))?$model->poster->profile->image:$this->context->module->getRegisteredImage('empty.jpeg'), ['alt'=>'avatar']); ?>
            </div> 
        </div>
        
        <div class="col-xs-9"> 
            <div class="member-mobile">
                <?php echo Html::a(Html::encode($model->poster->profile->username), ['member/view', 'id'=>$model->poster->id], ['class'=>'header2']); ?>
            </div>            
            <div class="pad5-left">
                <?= DateTimeCalculation::short($model->create_time) ?>
            </div>
        </div>
    </div>        
        
    <div class="post-content">
        <?php echo $model->content; ?>
    </div>
    
    <div class="signature">     
        <?php echo $model->poster->signature; ?>  
        <span class="pull-right"><?php echo $this->render('_upvotedBy', ['post_id'=>$model->id]); ?></span>
    </div>    
    
    <div class="row">
        <div class= "col-md-8">
            <div class="toolbar">               
                <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->id!=$model->poster->id): ?>
                    <?php echo $this->context->showUpvote($model->id); ?>
                    <?php echo Html::button(YBoard::t('yboard', 'report').' <span class="glyphicon glyphicon-flag"></span>', ['class'=>'btn btn-sm btn-default','title'=>YBoard::t('yboard', 'Report post'), 'style'=>'cursor:pointer;', 'onclick'=>'reportPost(' . $model->id . ')']); ?>
                <?php endif; ?>
                
                <?php if(!Yii::$app->user->isGuest && !$model->topic->locked || Yii::$app->user->can('moderator')): ?>
                    <!-- quote -->	
                    <?php $form = ActiveForm::begin([
                        'action'=>['forum/quote', 'id'=>$model->id],
                        'enableAjaxValidation'=>false,
                    ]) ?>
                        <?php echo Html::submitButton(YBoard::t('yboard','Quote').' <span class="glyphicon glyphicon-comment"></span>', ['class'=>'btn btn-default btn-sm']); ?>
                    <?php ActiveForm::end(); ?>
                    
                    <!-- reply -->
                    <?php $form = ActiveForm::begin([
                        'action'=>['forum/reply', 'id'=>$model->topic_id],
                        'enableAjaxValidation'=>false,
                    ]) ?>
                        <?php echo Html::submitButton(YBoard::t('yboard','Reply').' <span class="glyphicon glyphicon-share-alt"></span>', ['class'=>'btn btn-default btn-sm']); ?>
                    <?php ActiveForm::end(); ?>
                    
                    <!-- edit own post or moderator -->
                    <?php if($model->user_id == Yii::$app->user->id || Yii::$app->user->can('moderator')): ?>
                        <?php $form = ActiveForm::begin([
                            'action'=>['forum/update', 'id'=>$model->id],
                            'enableAjaxValidation'=>false,
                        ]) ?>
                            <?php echo Html::submitButton(YBoard::t('yboard','Edit').' <span class="glyphicon glyphicon-pencil"></span>', ['class'=>'btn btn-default btn-sm']); ?>
                        <?php ActiveForm::end(); ?>                        
                    <?php endif; ?>
                    
                <?php endif; ?>		                 
                <?php if(Yii::$app->user->can('moderator')): ?>
                    <!--Moderator Actions -->
                    <?= ButtonDropdown::widget([
                        'label' => YBoard::t('yboard','Mod'), 
                        'options'=>[
                            'class'=>'btn btn-default btn-sm pull-left',
                        ],
                        'dropdown' => [
                            'encodeLabels' =>false,
                            'items' =>array_merge(!Yii::$app->user->can('admin')||$model->original_post==1 ? [] : 
                            [
                                [
                                    'label' => YBoard::t('yboard','Delete post').' <span class="glyphicon glyphicon-trash"></span>', 
                                    'url' => '/',
                                    'options'=>[
                                        //'class'=>'btn btn-danger btn-sm',
                                    ],
                                    'linkOptions'=>[
                                        'onclick'=>'if(confirm("' . YBoard::t('yboard','Want to delete this post?') . '")) { deletePost("' . Yii::$app->urlManager->createAbsoluteUrl([$this->context->module->id.'/moderator/delete', 'id'=>$model->id]) . '"); return false; }',
                                    ],
                                ]
                            ], 
                            [                                
                                [
                                    'label' => YBoard::t('yboard','Disapprove post').' <span class="glyphicon glyphicon-remove-circle"></span>', 
                                    'url' => '#',
                                    'options'=>[
                                        //'class'=>'btn btn-danger btn-sm',
                                    ],
                                    'linkOptions'=>[
                                        'onclick'=>'if(confirm("' . YBoard::t('yboard','Want to Remove post from visible Posts?') . '")) { deletePost("' . Yii::$app->urlManager->createAbsoluteUrl([$this->context->module->id.'/moderator/disapprove', 'id'=>$model->id]) . '"); return false; }',
                                    ],
                                ],
                                [
                                    'label' => YBoard::t('yboard','Ban user').' <span class="glyphicon glyphicon-fire"></span>', 
                                    'url' => '',
                                    'options'=>[
                                        //'class'=>'btn btn-danger btn-sm',
                                    ],
                                    'linkOptions'=>[
                                    'title'=>YBoard::t('yboard', 'Ban this user'),
                                        'onclick'=>'if(confirm("' . YBoard::t('yboard','Do you really want to Ban this User?') . '")) { banUser('.Yii::$app->user->id.', "' . Yii::$app->urlManager->createAbsoluteUrl([$this->context->module->id.'/moderator/ban-user', 'id'=>$model->user_id]) . '");  }return false;',
                                    ],
                                ],
                                [
                                    'label' => YBoard::t('yboard','Ban user IP').' <span class="glyphicon glyphicon-shot"></span>', 
                                    'visible' =>Yii::$app->user->can('admin'),
                                    'url' => '/',
                                    'options'=>[
                                        //'class'=>'btn btn-danger btn-sm',
                                    ],
                                    'linkOptions'=>[
                                    'title'=>YBoard::t('yboard', 'Ban IP user\'saddress'),
                                        'onclick'=>'if(confirm("' . YBoard::t('yboard','Do you really want to ban this IP address?') . '")) { banIp(' . $model->id . ', "' . Yii::$app->urlManager->createAbsoluteUrl('moderator/ban-ip') . '"); }return false; ',
                                    ],
                                ],
                             ]),
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
            
        <div class= "col-md-4">
            <?php if($model->change_reason): ?>
                <?php echo YBoard::t('yboard','last modified on {date}, Reason: {reason}', 
                    [
                        'date'=> DateTimeCalculation::medium($model->change_time), 
                        'reason'=>Html::encode($model->change_reason)
                    ]
            ); ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="post-bottom"></div>
</div>


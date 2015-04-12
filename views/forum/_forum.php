<?php
use  app\modules\yboard\YBoard;
use yii\helpers\Html;
use app\modules\yboard\components\DateTimeCalculation;

/* @var $this ForumController */
/* @var $model YBoardForum */


?>

<div class="forum-container">    
        <div class="forum-category" onclick="YBoard.toggleForumGroup(<?php echo $model->id; ?>,'<?php echo \Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/forum/setCollapsed'); ?>');">
            <div class="header2">
                <div class="col-md-5"><span><?= Html::encode($model->name) ?></span> </div>
                <div class="col-md-2  forum-descr-title  hidden-xs"><?= YBoard::t('yboard', 'Posts') ?></div>
                <div class="col-md-2  forum-descr-title  hidden-xs"><?= YBoard::t('yboard', 'Topics') ?></div>
                <div class="col-md-3  forum-descr-title  hidden-xs"><?= YBoard::t('yboard', 'Last Post') ?></div> 
            </div>
            
            <div class="header5  hidden-xs">
                <span><?= Html::encode($model->subtitle) ?></span>
            </div>
        </div>
     
    
    <div class="forum-group" id="category_<?php echo $model->id; ?>"  <?php if($this->context->collapsed($model->id)) { echo 'style="display:none;"';}?>>
        <!-- do render forums here-->
        <?php /*set current user querying, -1 means admin */ $model->uid = Yii::$app->user->can('admin') ? -1: Yii::$app->user->id; ?>
        <?php foreach($model->forums as $forum): ?>
            
            <div class="col-md-12 single-foro-item">
            <?php 
            $foroimage = 'forum';
                if(!isset($forum->last_post_id) || $this->context->forumIsRead($forum->id)) {
                    $foroimage .= '1';
                } else {
                    $foroimage .= '2';
                }
                
                if($forum->locked) {
                    $foroimage .= 'l';
                }
                
                if($forum->moderated) {
                    $foroimage .= 'm';
                }
                 
            ?>
                <div class="forum-cell col-md-1 hidden-xs <?= $foroimage ?>"> </div>
                
                <div class="forum-cell col-md-4 main">
                    <div class="header2">
                        <?php echo Html::a(Html::encode($forum->name).($forum->public==1?'':' [Private]'), ['forum', 'id'=>$forum->id]); ?>
                    </div>
                    <div class="header5 pad5-bottom hidden-xs" style="text-align:justify;">
                        <?php echo Html::encode($forum->subtitle); ?> 
                        <?php if($index == $lastIndex){ echo '<p style="padding:5px;"></p>'; } ?> <!-- add few space -->

                    </div>
                </div>
                
                <div class="forum-cell col-md-2 center hidden-xs">
                    <?php echo Html::encode($forum->num_posts); ?>
                </div>
                
                <div class="forum-cell col-md-2 center hidden-xs">
                    <?php echo Html::encode($forum->num_topics); ?>
                </div>
                
                <div class="forum-cell col-md-3 last-cell">
                    <div class="hidden-xs"> 
                        <?php if($forum->last_post_id && $forum->lastPost) {
                            echo Html::a(Html::encode($forum->lastPost->topic->title), ['topic', 'id'=>$forum->lastPost->topic_id]);
                            
                            echo '<br>'.YBoard::t('yboard', 'Last post by') . ' ' .Html::encode($forum->lastPost->poster->profile->username);
                            
                            echo Html::a(Html::img($this->context->module->getRegisteredImage('next.png'), ['style'=>'margin-left:5px;', 'title'=>YBoard::t('yboard', 'view last post')]), ['topic', 'id'=>$forum->lastPost->topic_id, 'nav'=>'last']);
                            
                            echo '<br>';
                            
                            echo ' ' . YBoard::t('yboard', 'on') . ' ' . DateTimeCalculation::medium($forum->lastPost->create_time); 
                        } else {
                            echo YBoard::t('yboard', 'No posts');
                        }
                        ?>
                    </div>
                    
                    <div class="visible-xs">
                        <?= YBoard::t('yboard', '{topics, plural, =0{No Topic} =1{One Topic} other{# Topics}}. Last Post {time}', ['topics'=>$forum->num_topics==null? 0 : $forum->num_topics, 'time'=>DateTimeCalculation::short($forum->lastPost==null ? 0 : $forum->lastPost->create_time)]) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <!-- /do render forums here-->
    </div>
    
</div> 


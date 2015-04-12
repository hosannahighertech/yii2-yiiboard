<?php
/* @var $this ForumController */
use  app\modules\yboard\models\YBoardTopic;
use  app\modules\yboard\models\YBoardPost;
use  app\modules\yboard\models\YBoardMember;
use  app\modules\yboard\models\YBoardSession;
use \yii\helpers\Html;
use app\modules\yboard\YBoard;
?>
<div class="row hidden-xs"  id="yboard-footer">    
		<div class="col-md-5">
            <div class="row legend">
                <div class="col-md-12 header2">
                    <?php echo YBoard::t('yboard','Forum Legend'); ?>
                </div>                
            </div>
            
            <div class="row pad5"> 
                <div class="col-md-1 forum-cell topic1"> 
                </div>
                
                <div class="col-md-3">
                    <?php echo YBoard::t('yboard','Unread topic'); ?>
                </div>
                
                <div class="col-md-1 forum-cell topic1s"> 
                </div>
                
                <div class="col-md-3"> 
                    <?php echo YBoard::t('yboard','Sticky topic'); ?>
                </div>
                
                <div class="col-md-1 forum-cell topic1p"> 
                </div>
                
                <div class="col-md-3"> 
                    <?php echo YBoard::t('yboard','Poll'); ?>
                </div>
                
            </div>
            
            <div class="row  pad5"> 
                <div class="col-md-1 forum-cell topic2"> 
                </div>
                
                <div class="col-md-3"> 
                    <?php echo YBoard::t('yboard','Read topic'); ?>
                </div>
                
                <div class="col-md-1 forum-cell topic1l"> 
                </div>
                
                <div class="col-md-3"> 
                    <?php echo YBoard::t('yboard','Locked topic'); ?>
                </div>
                
                <div class="col-md-1 forum-cell topic1g"> 
                </div>
                
                <div class="col-md-3">
                    <?php echo YBoard::t('yboard','Global topic'); ?>
                </div>
            </div>
        </div> 
        
		<div class="col-md-7"> 
            <div class="row statistics">
                <div class="col-md-12 header2">
                    <?php echo YBoard::t('yboard','Board Statistics'); ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-2"><?php echo YBoard::t('yboard','Total topics'); ?></div> 
                <div class="col-md-1"><?php echo YBoardTopic::find()->count(); ?></div> 
                <div class="col-md-9"></div> 
            </div>
            
            <div class="row">
                <div class="col-md-2"><?php echo YBoard::t('yboard','Total posts'); ?></div> 
                <div class="col-md-1"><?php echo YBoardPost::find()->count(); ?></div> 
                <div class="col-md-9"></div> 
            </div>
            
            <div class="row">
                <div class="col-md-2"><?php echo YBoard::t('yboard','Total members'); ?></div> 
                <div class="col-md-1"><?php echo YBoardMember::find()->count(); ?></div> 
                <div class="col-md-9"></div> 
            </div>
            
            <div class="row">
                <div class="col-md-2"><?php echo YBoard::t('yboard','Newest member'); ?></div> 
                <div class="col-md-1"><?php $member = YBoardMember::find()->newestScope()->one(); echo $member==null?YBoard::t('yboard','None'):Html::a($member->profile->username, ['member/view', 'id'=>$member->id]); ?></div> 
                <div class="col-md-9"></div> 
            </div>
            
            <div class="row">
                <div class="col-md-2"><?php echo YBoard::t('yboard','Visitors today'); ?></div> 
                <div class="col-md-1"><?php echo YBoardSession::find()->count(); ?></div> 
                <div class="col-md-9"></div> 
            </div>
        </div> 
</div> 
<?= $this->render('_changeForum') ?> 

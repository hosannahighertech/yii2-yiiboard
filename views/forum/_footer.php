<?php
use app\modules\yboard\models\YBoardMember;
use app\modules\yboard\models\YBoardSession;
use app\modules\yboard\models\YBoardTopic;
use app\modules\yboard\models\YBoardPost;
use app\modules\yboard\models\YBoardSpider;
use yii\helpers\Html;
use  app\modules\yboard\YBoard;

/* @var $this ForumController */
$guests =  YBoardSession::find()->where('user_id IS NULL')->count();
$members =  YBoardSession::find()->where('user_id IS NOT NULL')->count();

?>
<div id="yboard-footer">
    <div class="row">
        <div class="online col-md-9">
			<div>
				<span class="header2" id="online-record"><?php echo YBoard::t('yboard','{{guests}} guest(s) and {{members}} active member(s)', ['{guests}'=>($guests), '{members}'=>$members]);?></span>
				<?php echo YBoard::t('yboard','(in the past 15 minutes)');?>
			</div>
			
            <div>
				<?php $members = YBoardMember::find()->presentScope()->all(); 
					foreach($members as $member) {
                        echo Html::a($member->profile->username, ['member/view', 'id'=>$member->id], ['style'=>'color:#'.($member->group==null?'':$member->group->color)]) . '&nbsp;';
					}
					$spiders = YBoardSpider::find()->presentScope()->all(); 
					foreach($spiders as $spider) {
						echo Html::a($spider->name, $spider->url, array('class'=>'spider','target'=>'_new')) . '&nbsp;';
					}
				?>
				<?php echo YBoard::t('yboard','({hidden} anonymous member(s))', array('hidden'=>YBoardMember::find()->hiddenScope()->presentScope()->count())); ?>
			</div>
		</div>
		
        <div class="statistics col-md-3"> 
			<span class="header2">
				<?php echo YBoard::t('yboard','Board Statistics'); ?>
			</span>
            
			<div class="row">
				<div class="col-md-6"><?php echo YBoard::t('yboard','Total topics'); ?></div>
                <div class="col-md-6"><?php echo  YBoardTopic::find()->count(); ?></div>
			</div>
            
            <div class="row">
				<div class="col-md-6"><?php echo YBoard::t('yboard','Total posts'); ?></div>
                <div class="col-md-6"><?php echo YBoardPost::find()->count(); ?></div>
			</div>
            
            <div class="row">
				<div class="col-md-6"><?php echo YBoard::t('yboard','Total members'); ?></div>
                <div class="col-md-6"><?php echo  YBoardMember::find()->count(); ?></div>
			</div>
            
            <div class="row">
				<div class="col-md-6"><?php echo YBoard::t('yboard','Newest member'); ?></div>
                <div class="col-md-6"> <?php $member = YBoardMember::find()->newestScope()->one(); 
                echo $member===null?'&nbsp;'.YBoard::t('yboard','No new member yet'):Html::a($member->profile->username, ['member/view', 'id'=>$member->id]); ?></div>
			</div>
            
            <!--div class="row">
				<div class="col-md-6"><?php //echo YBoard::t('yboard','Visitors today'); ?></div>
                <div class="col-md-6"><?php //echo YBoardSession::find()->count(); ?></div>
			</div-->             
		</div>
    </div><br>
</div>  
<?= $this->render('_changeForum') ?>

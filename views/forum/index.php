<?php

use yii\helpers\Html;
use yii\widgets\ListView; 
use  app\modules\yboard\YBoard;
use app\modules\yboard\components\DateTimeCalculation;



/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\yboard\models\YBoardForumSearch $searchModel
/* @var $this ForumController */ 


$this->title = $this->context->module->forumTitle;
$this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs'] = [
	YBoard::t('yboard', 'Forums'),
];   

?>

<div id="yboard-wrapper" >	
    <div class="row">
        <div class="col-md-9 col-sm-12">
            <?= ListView::widget([ 
                'itemOptions' => ['class' => 'item'], 
                'id'=>'yboardForum',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_forum',
                'viewParams'=>['lastIndex'=>($dataProvider->count - 1)],
                'summary'=>false,
            ]) ?> 
        </div>
        
        <div class="col-md-3 hidden-xs" id="sidebar">
            <div class="portlet">
                <div class="header header2"><?= YBoard::t('yboard','Latest Topics') ?></div>
                <div class="contents">
                    <?php $idx=0; ?>
                    <?php foreach($recentTopics as $topic): ?>
                        <div class="<?= $idx%2==0?'even' : 'odd'?>">
                            <?= YBoard::t('yboard', '{topic} by {user}. {time}', ['topic'=>Html::a($topic->title, ['topic', 'id'=>$topic->id]), 'user'=>Html::a($topic->starter->profile->username, ['member/view', 'id'=>$topic->starter->id]), 'time'=>DateTimeCalculation::medium($topic->lastPost->create_time)]) ?><br>
                            <?php $idx = $idx+1; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div> 
            
            <div class="portlet">
                <div class="header header2"><?= YBoard::t('yboard','Recent Replies') ?></div>
                <div class="contents">
                    <?php $idx=0; ?>
                    <?php foreach($recentReplies as $post): ?>
                        <div class="<?= $idx%2==0?'even' : 'odd'?>">
                            <?= YBoard::t('yboard', '{user} replied to {topic} on {time}', ['topic'=>Html::a($post->topic->title, ['topic', 'id'=>$post->topic->id]), 'user'=>Html::a($post->poster->profile->username, ['member/view', 'id'=>$topic->starter->id]), 'time'=>DateTimeCalculation::medium($post->create_time)]) ?><br>
                            <?php $idx = $idx+1; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>     
       
    <div class="hidden-xs">
        <?= $this->render('_footer'); ?> 
    </div>
</div>

 

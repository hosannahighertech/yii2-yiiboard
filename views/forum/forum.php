<?php
/* @var $this ForumController */
/* @var $forum YBoardForum */
/* @var $dataProvider DataProvider */
  
use app\modules\yboard\YBoard;
use app\modules\yboard\models\YBoardPost;
use app\modules\yboard\models\YBoardTopic;
use app\modules\yboard\models\YBoardMessage;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\Dialog;
use yii\web\JsExpression; 

$this->params['breadcrumbs'] = [
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	$forum->name,
];

$approvals = YBoardPost::find()->unapprovedScope()->count();
$reports = YBoardMessage::find()->reportScope()->count();

$this->title = $forum->name;
?>

<?php  if(\Yii::$app->session->hasFlash('moderation')): ?>
<div class="flash-notice">
	<?php echo \Yii::$app->session->getFlash('moderation'); ?>
</div>
<?php endif; ?>

<div id="yboard-wrapper" class="container">
	
	<?php if(!\Yii::$app->user->isGuest && !$forum->locked || Yii::$app->user->can('moderator')): ?>
        <div class="row">  
            <div>
                <?php $form = ActiveForm::begin([
                        'id'=>'create-topic-form', 
                        'action'=>['forum/create-topic'],
                        'enableAjaxValidation'=>false,
                    ]) ?> 
                    <?= Html::activeHiddenInput($forum, 'id') ?> 
                    <?= Html::submitButton(YBoard::t('yboard','New Topic'), ['class'=>'btn btn-info btn-sm pull-right']) ?>
                 <?php ActiveForm::end(); ?>        
            </div><!-- form -->	
        </div> <br>
	<?php endif; ?>
    
	<div class="row">
        <div class="forum-category col-md-12">
            <div class="header2">
                <?php  echo $forum->name; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"> 
             <?= ListView::widget([ 
                'summary'=>false, //YBoard::t('yboard', 'Showing {count} of {totalCount} topics'),
                'itemView'=>'_topic',
                'id'=>'yboardTopic',
                'dataProvider'=>$dataProvider,
            ])     
            ?>
        </div>
       
    </div>
    
    <div>
        <?php echo $this->render('_forumfooter'); ?>
    </div>
</div>

<div style="display:none;">
<?php 
if(Yii::$app->user->can('moderator')) {
    Dialog::begin([
		'id'=>'dlgTopicForm',
        'clientOptions' => [
            'modal' => true,
			'title'=>YBoard::t('yboard', 'Update topic'),
            'autoOpen'=>false,
            'modal'=>true,   
            'height'=>400,
            'width'=>300,  
            'buttons'=> [ 
                [
                    'text'=>YBoard::t('yboard', 'Change'), 
                    'class'=>'btn btn-sm btn-success', 
                    'click'=>new JsExpression(' function() { YBoard.changeTopic("' . \Yii::$app->urlmanager->createAbsoluteUrl($this->context->module->id.'/moderator/change-topic') . '"); }')
                ],
                [
                    'text'=>YBoard::t('yboard', 'Cancel'), 
                    'class'=>'btn btn-sm btn-danger', 
                    'click'=>new JsExpression(' function() { $( this ).dialog( "close" ); }')
                ]
            ],
        ],
    ]);

		echo $this->render('_topicForm', array('model'=>new YBoardTopic));

    Dialog::end();
}
?>
</div>

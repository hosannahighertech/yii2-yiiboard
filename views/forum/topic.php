<?php
/* @var $this ForumController */
/* @var $forum YBoardForum */
/* @var $topic YBoardTopic */
/* @var $dataProvider CActiveDataProvider */
/* @var $postId integer */


use app\modules\yboard\YBoard;
use app\modules\yboard\models\YBoardPost;
use app\modules\yboard\models\YBoardMessage;
use yii\web\View;
use yii\bootstrap\Alert;
use app\components\ckeditor\CKEditor;

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\Dialog;

$this->params['breadcrumbs']=[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	['label'=>$forum->name, 'url'=>['forum/forum', 'id'=>$forum->id]],
    $topic->title,
];


$this->title = Html::encode($topic->title); 

$this->registerJs('language', "
	var language = \"" . substr(Yii::$app->language, 0, 2) . "\";", 
View::POS_BEGIN);

$this->registerJs('scrollToPost', "
	var aTag = $('a[name=\"" . $postId . "\"]');
	if(aTag.length > 0) {
		$('html,body').animate({scrollTop: aTag.offset().top},'fast');
	}
");

?>

<?php if(Yii::$app->session->hasFlash('moderation')): ?>
    <?= Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body' => Yii::$app->session->getFlash('moderation')
    ])?> 
    
<?php endif; ?>

<div id="yboard-wrapper" class="container">	
	<?php if(!(Yii::$app->user->isGuest || $topic->locked) || Yii::$app->user->can('moderator')): ?>
	<div class="form row hidden-xs"> 
        <div>
            <?php $form = ActiveForm::begin([
                    'id'=>'create-post-form',
                    'action'=>['forum/reply', 'id'=>$topic->id],
                    'enableAjaxValidation'=>false,
            ]) ?> 
                <?= Html::activeHiddenInput($forum, 'id') ?>
                <?php echo Html::submitButton(YBoard::t('yboard','New Reply'), ['class'=>'btn btn-info btn-sm pull-right', 'style'=>'margin:5px;']); ?>
            <?php ActiveForm::end(); ?>
        </div><!-- form -->	
	</div> 
	<?php endif; ?>

    <div class="row"> 
         <?= ListView::widget([ 
            'summary'=>'', //YBoard::t('yboard', 'Showing {begin} of {totalCount} posts'),
            'id'=>'yboardPost',
            'itemView'=>'_post', 
            'dataProvider'=>$dataProvider,
            'viewParams'=>['postId'=>$postId, 'dataProvider'=>$choiceProvider, 'topic'=>$topic],
        ]) ?>
    </div>

<?php if(!Yii::$app->user->isGuest): ?>
    <div class="row pad5-bottom">
        <?php $form = ActiveForm::begin([
            'id'=>'create-topic-form',
            'action'=>['/'.$this->context->module->id.'/forum/reply', 'id'=>$reply->topic_id],
            'enableAjaxValidation'=>false,
        ]) ?>
        
            <?= $form->errorSummary($reply) ?>
            
            <?= Html::activeHiddenInput($reply,'subject') ?>

            <?= CKEditor::widget([
                'model' => $reply,
                'attribute'=>'content',
            ]) ?> 

            <?= $reply->isNewRecord?"":$form->field($reply,'change_reason')->textInput() ?>
            
            <?= Html::activeHiddenInput($reply, 'forum_id') ?>
            <?= Html::activeHiddenInput($reply, 'topic_id') ?>
            
            <br><?= Html::submitButton($reply->isNewRecord?YBoard::t('yboard','Reply'):YBoard::t('yboard','Save'), ['class'=>'btn btn-primary btn-sm']) ?>
        
        <?php ActiveForm::end(); ?>
    </div><!-- form -->	
<?php endif; ?>
</div>

<div style="display:none;">
<?php
if(!Yii::$app->user->isGuest)
{
    Dialog::begin([
        'id'=>'dlgReportForm',
        'clientOptions' => [
            'modal' => true,
            'title'=>YBoard::t('yboard', 'Report post'),
            'autoOpen'=>false,
            'modal'=>true,   
            'height'=>'auto',
            'width'=>'auto', 
                        
            'buttons'=>[
                [
                    'text'=>YBoard::t('yboard', 'Send'), 
                    'class'=>'btn btn-sm btn-success', 
                    'click'=>new \yii\web\JsExpression('function(){ 
                        for(instance in CKEDITOR.instances)
                        {
                            CKEDITOR.instances[instance].updateElement();
                        }
                        sendReport(); 
                    }')
                ],		
                [
                    'text'=>YBoard::t('yboard', 'Cancel'), 
                    'class'=>'btn btn-sm btn-danger', 
                    'click'=>new \yii\web\JsExpression(' function() { $( this ).dialog( "close" ); }')
                ]			
            ],
        ],
    ]);

        echo $this->render('_reportForm', ['model'=>new YBoardMessage]);

    Dialog::end();

    Dialog::begin([
        'id'=>'dlgPrivateMsg',
        'clientOptions' => [
            'modal' => true,
            'title'=>YBoard::t('yboard', 'Send Private Message'),
            'autoOpen'=>false,
            'modal'=>true,   
            'height'=>'auto',
            'width'=>'auto',  
            'buttons'=>[
                [
                    'text'=>YBoard::t('yboard', 'Send'), 
                    'class'=>'btn btn-sm btn-success', 
                    'click'=>new \yii\web\JsExpression('function(){ 
                        for(instance in CKEDITOR.instances)
                        {
                            CKEDITOR.instances[instance].updateElement();
                        }
                        sendPMForm(); 
                    }')
                ],		
                [
                    'text'=>YBoard::t('yboard', 'Cancel'), 
                    'class'=>'btn btn-sm btn-danger', 
                    'click'=>new \yii\web\JsExpression(' function() { $( this ).dialog( "close" ); }')
                ]			
            ],
        ],
    ]);

        echo $this->render('_PrivateMsgForm', ['model'=>new YBoardMessage(['sendfrom'=>Yii::$app->user->identity->id, 'sendto'=>0])]);

    Dialog::end();
}


//for viewing who appreciated post
Dialog::begin([
    'id'=>'dlg-appreciated',
    'clientOptions' => [
        'modal' => true,
        'title'=>YBoard::t('yboard', 'Member Appreciated'),
        'autoOpen'=>false,
        'modal'=>true,   
        'height'=>'auto',
        'width'=>'300',  
        'buttons'=>[ 
            [
                'text'=>YBoard::t('yboard', 'close'), 
                'class'=>'btn btn-sm btn-default', 
                'click'=>new \yii\web\JsExpression(' function() { $( this ).dialog( "close" ); }')
            ]			
            ],
    ],
]);

echo '<div id="appreciated-content" ></div>';

Dialog::end();

?>
</div>

<?php
//for banning Users
Dialog::begin([
    'id'=>'dlg-ban',
    'clientOptions' => [
        'modal' => true,
        'title'=>YBoard::t('yboard', 'Ban User'),
        'autoOpen'=>false,
        'modal'=>true,   
        'height'=>'auto',
        'width'=>'300',  
    ],
]);

        echo $this->render('_banForm', ['model'=>new YBoardMessage(['sendfrom'=>Yii::$app->user->id, 'sendto'=>0])]);

Dialog::end();

?>

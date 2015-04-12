<?php
/* @var $this ForumController */
/* @var $forum YBoardForum */
/* @var $post YBoardPost */
/* @var $poll YBoardPoll */
/* @var $choices array */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ckeditor\CKEditor;
use  app\modules\yboard\YBoard;
 
$this->params['breadcrumbs']=[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	['label'=>$forum->name,  'url'=> ['forum/forum', 'id'=>$forum->id]],
	YBoard::t('yboard', 'New topic'),
];

$items = array(
	array('label'=>YBoard::t('yboard', 'Forums'), 'url'=>array('forum/index')),
	array('label'=>YBoard::t('yboard', 'Members'), 'url'=>array('member/index'))
);

$this->title = YBoard::t('yboard', '{forum} - New Topic', ['forum'=>$forum->name]);

if(empty($poll->question) && !$poll->hasErrors()) {
	$show = false;
} else {
	$show = true;
}
?>
<div id="yboard-wrapper">
	<noscript>
        <div class="flash-notice">
            <?php echo YBoard::t('yboard','Your web browser does not support JavaScript, or you have temporarily disabled scripting. This site needs JavaScript to function correct.'); ?>
        </div>
	</noscript>

    <div class="pad15">
        <?php $form = ActiveForm::begin([
            'id' => 'create-topic-form',
            'enableAjaxValidation'=>false,
        ]); ?>
        
        <?= $form->errorSummary($post) ?>
        <?= Html::errorSummary($poll) ?>

 		
		<?php if($forum->poll == 2 || ($forum->poll == 1 && Yii::$app->user->can('moderator'))): ?>
			<div class="row" style="<?php echo ($show?'display:none;':''); ?>">
                <div class="col-md-12 yboard-poll-button" id="poll-button" >
                    <?php echo Html::button(YBoard::t('yboard','Add poll').' <span class="glyphicon glyphicon-list-alt"></span>', ['class'=>'btn btn-primary btn-sm','onclick'=>'showPoll()']); ?>
                </div>
            </div>
            
			<div class="row poll-form-showhide" style="<?php echo ($show?'':'display:none;'); ?>">
                <div class="col-md-12 yboard-poll-button" >
                    <?php echo Html::hiddenInput('addPoll','no', ['id'=>'addPoll']); ?>
                    <?php echo Html::button(YBoard::t('yboard','Remove poll').' <span class="glyphicon glyphicon-remove"></span>',  ['class'=>'btn btn-primary btn-sm','onclick'=>'hidePoll()']); ?>
                </div>
            </div>
            
			<div class="col-md-12 poll-form poll-form-showhide panel yboard-panel" style="<?php echo ($show?'':'display:none;'); ?>" class="yboard-poll-form">
                <div class="form-group">
                    <?php echo Html::label($poll->getAttributeLabel('question'), false, ['class'=>'form-label']); ?>
                    <?php echo Html::activeTextInput($poll,'question',['maxlength'=>255, 'class'=>'form-control']); ?>
                </div>
                
                <div class="form-group" id="poll-choices">
                    <?php echo Html::label(YBoard::t('yboard','Choices'),false, ['class'=>'form-label']); ?>
					<?php foreach($choices as $key => $value): ?>
                        <div class="pad5-top">
                            <?php echo Html::textInput('choice['.$key.']',$value,[
                                'maxlength'=>80, 
                                'class'=>'form-control',
                                'id'=>$key+1,
                                'onchange'=>'pollChange(this)',
                            ]); ?>
                        </div>
					<?php endforeach; ?>
                </div>

				<div>
					<?php echo Html::activeCheckbox($poll,'allow_revote'); ?> &nbsp;
					<?php echo Html::activeCheckbox($poll,'allow_multiple'); ?> &nbsp;
					<?php echo Html::activeHiddenInput($poll,'expire_date', ['id'=>'expire_date']); ?>
                    
                        <strong>
                            <?php echo YBoard::t('yboard','Poll expires'); ?>:
                        </strong>

                        <?= \yii\jui\DatePicker::widget([
                            'name' => 'expiredate',  
                            'dateFormat' => 'yyyy-MM-dd',
                            'clientOptions' => [
                                'defaultDate' => $poll->expire_date,
                                'showAnim'=>'fold',
                                'onSelect' =>new \yii\web\JsExpression('function(date, picker){
                                    $("#expire_date").val(date);
                                }')
                            ], 
                        ]) ?> 
				</div>
                            
			</div>
		<?php endif; ?>
        
        
        <!-- Form fields continues --->
        <?= $form->field($post, 'subject') ?> 
                    
        <?= CKEditor::widget([
            'model' => $post,
            'attribute'=>'content',
        ]) ?> 

		
		<?php if(Yii::$app->user->can('moderator')): ?>
		
			<div class="row">
                <div class="col-md-12">
                    <strong><?php echo YBoard::t('yboard','Sticky'); ?>:</strong>
                    <?php echo Html::checkbox('sticky'); ?> &nbsp; 
                    <strong><?php echo YBoard::t('yboard','Global'); ?>:</strong>
                    <?php echo Html::checkbox('global'); ?> &nbsp; 
                    <strong><?php echo YBoard::t('yboard','Locked'); ?>:</strong>
                    <?php echo Html::checkbox('locked'); ?> &nbsp; 
                </div>
			</div>
		
		<?php endif; ?>
		
        <?php echo Html::activeHiddenInput($post, 'forum_id'); ?><br> 
       <?php echo Html::submitButton(YBoard::t('yboard','Save Post'),['class'=>'btn btn-primary btn-md']); ?>
    <?php ActiveForm::end(); ?>
	</div><!-- form -->	<br>

</div>

<?php
/* @var $this ForumController */
/* @var $model YBoardTopic */
/* @var $form ActiveForm */


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\yboard\YBoard;
use app\modules\yboard\models\YBoardForum;
?>

<div class="form">
	
    <?php $form = ActiveForm::begin([
		'id'=>'update-topic-form',
        'action'=>['forum/quote', 'id'=>$model->id],
        'enableAjaxValidation'=>true, 
        'validateOnSubmit'=>true,
        'validateOnChange'=>false, 
    ]) ?>

		<?= $form->errorSummary($model)  ?>
		
        <?= $form->field($model,'forum_id')->dropDownList(ArrayHelper::map(YBoardForum::find()->forumScope()->all(), 'id', 'name'), [
            'onchange'=>'refreshTopics(this, "' . \Yii::$app->urlManager->createAbsoluteUrl('moderator/refreshTopics') . '")',
            'id'=>'YBoardTopic_forum_id',
        ])  ?>

        <?= $form->field($model,'merge')->dropDownList([], ['id'=>'YBoardTopic_merge'])  ?>

        <?= $form->field($model,'title')->textInput(['id'=>'YBoardTopic_title']) ?>

        <?= $form->field($model,'locked')->dropDownList(['0'=>YBoard::t('yboard','No'), '1'=>YBoard::t('yboard','Yes')], ['id'=>'YBoardTopic_locked'])  ?>

        <?= $form->field($model,'sticky')->dropDownList(['0'=>YBoard::t('yboard','No'), '1'=>YBoard::t('yboard','Yes')], ['id'=>'YBoardTopic_sticky'])  ?>

        <?= $form->field($model,'global')->dropDownList(['0'=>YBoard::t('yboard','No'), '1'=>YBoard::t('yboard','Yes')], ['id'=>'YBoardTopic_global'])  ?>
        
        <?= $form->field($model,'approved')->dropDownList(['0'=>YBoard::t('yboard','No'), '1'=>YBoard::t('yboard','Yes')], ['id'=>'YBoardTopic_approved'])  ?>
    
        <?= Html::activeHiddenInput($model, 'id', ['id'=>'yboard-post-update-id']) ?> 
	
    <?php ActiveForm::end(); ?>
</div><!-- form -->	

<?php
/* @var $this ForumController */
/* @var $post YBoardPost */
/* @var $form ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ckeditor\CKEditor;
use app\modules\yboard\YBoard;
?>
<noscript>
<div class="flash-notice">
<?= YBoard::t('yboard','Your web browser does not support JavaScript, or you have temporarily disabled scripting. This site needs JavaScript to function correct.') ?>
</div>
</noscript>
<div class="form pad5-bottom">
    <?php $form = ActiveForm::begin([
		'id'=>'create-topic-form',
		'enableAjaxValidation'=>false,
    ]) ?>
    
		<?= $form->errorSummary($post) ?>
		
        <?= isset($hide_title)?Html::activeHiddenInput($post,'subject') :$form->field($post,'subject')->textInput() ?>

		<?= CKEditor::widget([
            'model' => $post,
            'attribute'=>'content',
        ]) ?> 

        <?= $post->isNewRecord?"":$form->field($post,'change_reason')->textInput() ?>
		
        <?= Html::activeHiddenInput($post, 'forum_id') ?>
        <?= Html::activeHiddenInput($post, 'topic_id') ?>
        
        <br><?= Html::submitButton($post->isNewRecord?YBoard::t('yboard','Post'):YBoard::t('yboard','Save'), ['class'=>'btn btn-primary btn-md']) ?>
	
    <?php ActiveForm::end(); ?>
</div><!-- form -->	

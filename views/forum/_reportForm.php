<?php
/* @var $this ForumController */
/* @var $model YBoardMessage */
/* @var $form ActiveForm */

use yii\helpers\Html; 
use yii\widgets\ActiveForm;
use app\components\ckeditor\CKEditor;
?>

<div class="form">
 <?php $form = ActiveForm::begin([
        'id'=>'report-form',
        'enableAjaxValidation'=>true,
]) ?> 

		<?= CKEditor::widget([
            'model' => $model,
            'attribute'=>'content',
        ]) ?> 
        
        <?= Html::activeHiddenInput($model, 'post_id', ['id'=>'YBoardMessage_post_id']) ?>
		<?= Html::hiddenInput('url', \Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/message/send-report'), ['id'=>'url']) ?>
	
<?php ActiveForm::end(); ?>

</div><!-- form -->

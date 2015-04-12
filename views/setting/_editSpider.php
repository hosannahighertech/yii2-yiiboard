<?php
/* @var $this SettingController */
/* @var $model YBoardSpider */
/* @var $form ActiveForm */


use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\modules\yboard\YBoard;
?>

<div class="form">

<?php $form=ActiveForm::begin([
	'id'=>'edit-spider-form',
	'enableAjaxValidation'=>true,
]); ?>

	<p class="note"><?php echo YBoard::t('yboard', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>
    <?php echo $form->field($model,'name')->textInput(['id'=>'YBoardSpider_name']); ?>
    <?php echo $form->field($model,'user_agent')->textInput(['id'=>'YBoardSpider_user_agent']); ?>
    <?php echo Html::activeHiddenInput($model,'id', ['id'=>'YBoardSpider_id']); ?>
	
<?php ActiveForm::end(); ?>

</div><!-- form -->

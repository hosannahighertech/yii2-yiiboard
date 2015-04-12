<?php
/* @var $this SettingController */
/* @var $model YBoardForum */
/* @var $form CActiveForm */

use \app\modules\yboard\models\YBoardForum;
use \app\modules\yboard\models\YBoardMembergroup;
use app\modules\yboard\YBoard;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>

<div class="form">

<?php $form = \yii\widgets\ActiveForm::begin([
	'id'=>'edit-forum-form',
	'enableAjaxValidation'=>true,
	/*'clientOptions'=>array(
		'validateOnSubmit'=>true,
		'validateOnChange'=>false,
	) */
]); ?>

	<p class="alert alert-warning"><?php echo YBoard::t('yboard', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?> 

    <?php echo $form->field($model,'name')->textInput(['id'=>'YBoardForum_name']); ?>

    <?php echo $form->field($model,'subtitle')->textArea(['id'=>'YBoardForum_subtitle']); ?>

    <?php echo $form->field($model,'sort')->textInput(['id'=>'YBoardForum_sort']); ?>
    
    <span class="YBoardForum_category">
        <?php echo $form->field($model,'cat_id')->dropDownList(ArrayHelper::map(YBoardForum::find()->categoriesScope()->all(), 'id', 'name'),['prompt'=>YBoard::t('yboard', '-- Select Category --'), 'id'=>'YBoardForum_category']); ?>
    </span>

    
    <span class="YBoardForum_public">
        <?php echo $form->field($model,'public')->dropDownList(['0'=>YBoard::t('yboard', 'No'),'1'=>YBoard::t('yboard', 'Yes')], ['id'=>'YBoardForum_public']); ?>
    </span>

    <span class="YBoardForum_locked">
        <?php echo $form->field($model,'locked')->dropDownList(['0'=>YBoard::t('yboard', 'No'),'1'=>YBoard::t('yboard', 'Yes')], ['id'=>'YBoardForum_locked']); ?>
    </span>
    
    <span class="YBoardForum_moderated">
        <?php echo $form->field($model,'moderated')->dropDownList(['0'=>YBoard::t('yboard', 'No'),'1'=>YBoard::t('yboard', 'Yes')], ['id'=>'YBoardForum_moderated']); ?>
    </span>

    <?php echo $form->field($model,'membergroup_id')->dropDownList(array_merge([0=>'All Members'], ArrayHelper::map(YBoardMembergroup::find()->specificScope()->all(), 'id', 'name')), ['id'=>'YBoardForum_membergroup']); ?>


    <span class="YBoardForum_polls">
        <?php echo $form->field($model,'poll')->dropDownList(['0'=>YBoard::t('yboard', 'No polls'),'1'=>YBoard::t('yboard', 'Moderator polls'),'2'=>YBoard::t('yboard', 'User polls')], ['id'=>'YBoardForum_polls']); ?>
    </span>
    
    <span class="YBoardForum_type">
        <?php echo $form->field($model,'type')->dropDownList(['0'=>YBoard::t('yboard', 'Category'),'1'=>YBoard::t('yboard', 'Forum')], ['id'=>'YBoardForum_type']); ?>
    </span>

    <?php echo Html::activeHiddenInput($model,'id', ['id'=>'YBoardForum_id']); ?>

<?php \yii\widgets\ActiveForm::end(); ?>

</div><!-- form -->

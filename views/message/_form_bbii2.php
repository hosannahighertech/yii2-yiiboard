<?php
/* @var $this MessageController */
/* @var $model YBoardMessage */
/* @var $form CActiveForm */
?>
<noscript>
<div class="flash-notice">
<?php echo Yii::t('YBoardModule.yboard','Your web browser does not support JavaScript, or you have temporarily disabled scripting. This site needs JavaScript to function correct.'); ?>
</div>
</noscript>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'message-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
	<?php if($this->action->id == 'create'): ?>
		<?php echo $form->labelEx($model,'sendto'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'attribute'=>'search',
				'model'=>$model,
				'sourceUrl'=>array('member/members'),
				'theme'=>$this->module->juiTheme,
				'options'=>array(
					'minLength'=>2,
					'delay'=>200,
					'select'=>'js:function(event, ui) { 
						$("#YBoardMessage_search").val(ui.item.label);
						$("#YBoardMessage_sendto").val(ui.item.value);
						return false;
					}',
				),
				'htmlOptions'=>array(
					'style'=>'height:20px;',
				),
			)); 
		?>
	<?php else: ?>
		<?php echo $form->label($model,'sendto'); ?>
		<strong><?php echo CHtml::encode($model->search); ?></strong>
	<?php endif; ?>
		<?php echo $form->hiddenField($model,'sendto'); ?>
		<?php echo $form->error($model,'sendto'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>100,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>
	
	<div class="row">
		<?php $this->widget($this->module->id.'.extensions.editMe.widgets.ExtEditMe', array(
			'model'=>$model,
			'attribute'=>'content',
			'autoLanguage'=>false,
			'height'=>'300px',
			'toolbar'=>array(
				array(
					'Bold', 'Italic', 'Underline', 'RemoveFormat'
				),
				array(
						'TextColor', 'BGColor',
				),
				'-',
				array('Link', 'Unlink', 'Image'),
				'-',
				array('Blockquote'),
			),
			'skin'=>$this->module->editorSkin,
			'uiColor'=>$this->module->editorUIColor,
			'contentsCss'=>$this->module->editorContentsCss,
		)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo $form->hiddenField($model,'type'); ?>
		<?php echo CHtml::submitButton(Yii::t('YBoardModule.yboard', 'Send')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
/* @var $this ForumController */
/* @var $model YBoardSetting */

$this->yboard_breadcrumbs=array(
	Yii::t('YBoardModule.yboard', 'Forum')=>array('forum/index'),
	Yii::t('YBoardModule.yboard', 'Settings')=>array('setting/index'),
	Yii::t('YBoardModule.yboard', 'Moderators')
);

$item = array(
	array('label'=>Yii::t('YBoardModule.yboard', 'Settings'), 'url'=>array('setting/index')),
	array('label'=>Yii::t('YBoardModule.yboard', 'Forum layout'), 'url'=>array('setting/forum')),
	array('label'=>Yii::t('YBoardModule.yboard', 'Member groups'), 'url'=>array('setting/group')),
	array('label'=>Yii::t('YBoardModule.yboard', 'Moderators'), 'url'=>array('setting/moderator')),
	array('label'=>Yii::t('YBoardModule.yboard', 'Webspiders'), 'url'=>array('setting/spider')),
);
?>
<div id="yboard-wrapper">
	<?php echo $this->renderPartial('_header', array('item'=>$item)); ?>
	
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'yboard-member-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'rowCssClassExpression'=>'(Yii::app()->authManager && Yii::app()->authManager->checkAccess("moderator", $data->id))?"moderator":(($row % 2)?"even":"odd")',
		'columns'=>array(
			'member_name',
			array(
				'name'=>'group_id',
				'value'=>'$data->group->name',
				'filter'=>CHtml::listData(YBoardMembergroup::model()->findAll(), 'id', 'name'),
			),
			array(
				'name'=>'moderator',
				'value'=>'CHtml::checkBox("moderator", $data->moderator, array("onclick"=>"changeModeration(this,$data->id,\'' . $this->createAbsoluteUrl('setting/changeModerator') . '\')"))',
				'type'=>'raw',
				'filter'=>array('0'=>Yii::t('YBoardModule.yboard', 'No'), '1'=>Yii::t('YBoardModule.yboard', 'Yes')),
				'htmlOptions'=>array("style"=>"text-align:center"),
			),
			
		),
	)); ?>
</div>

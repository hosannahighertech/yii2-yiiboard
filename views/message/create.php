<?php
/* @var $this MessageController */
/* @var $model YBoardMessage */
/* @var $count Array */

$this->yboard_breadcrumbs=array(
	Yii::t('YBoardModule.yboard', 'Forum')=>array('forum/index'),
	($this->action->id == 'create')?Yii::t('YBoardModule.yboard', 'New message'):Yii::t('YBoardModule.yboard', 'Reply'),
);

$item = array(
	array('label'=>Yii::t('YBoardModule.yboard', 'Inbox') .' ('. $count['inbox'] .')', 'url'=>array('message/inbox')),
	array('label'=>Yii::t('YBoardModule.yboard', 'Outbox') .' ('. $count['outbox'] .')', 'url'=>array('message/outbox')),
	array('label'=>Yii::t('YBoardModule.yboard', 'New message'), 'url'=>array('message/create'))
);
?>
<div id="yboard-wrapper">
	<?php echo $this->renderPartial('_header', array('item'=>$item)); ?>

	<h1><?php echo ($this->action->id == 'create')?Yii::t('YBoardModule.yboard', 'New message'):Yii::t('YBoardModule.yboard', 'Reply'); ?></h1>

	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
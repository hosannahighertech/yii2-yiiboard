<?php
/* @var $this ForumController */
/* @var $choiceProvider ActiveDataProvider */

use yii\helpers\Html;
use yii\widgets\ListView;


echo Html::beginForm('', 'post', array('id'=>'yboard-poll-form'));
echo Html::hiddenInput('poll_id', $this->context->poll->id);

echo ListView::widget([
	'id'=>'yboardPoll',
	'dataProvider'=>$choiceProvider,
	'itemView'=>'_pollChoice',
	'summary'=>false,
]);
echo '<div>';
echo Html::button(YBoard::t('yboard', 'Vote'), [
    'class'=>'btn btn-default btn-sm','onclick'=>'vote("' . Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/forum/vote') . '");'
]);
echo '</div>';
echo Html::endForm();
?>

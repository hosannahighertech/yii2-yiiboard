<?php
/* @var $this SettingCondivoller */
/* @var $forumdata YBoardForum (forum) */

use yii\helpers\Html;
use app\modules\yboard\YBoard;
?>

<div class="manage-forum">
	<div class="row">
		<div class="col-md-7">
			<span class="header2"><?php echo Html::encode($forumdata->name); ?></span>
			<?php echo Html::button(YBoard::t('yboard','Edit Forum'), ['class'=>'btn btn-primary btn-xs','onclick'=>'editForum(' . $forumdata->id . ',"' . YBoard::t('yboard','Edit forum') . '", "' . \Yii::$app->urlmanager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/get-forum') .'")']); ?>
            <p class="header5">
                <?php echo Html::encode($forumdata->subtitle); ?>
            </p>

		</div>
        
		<div class="col-md-5"> 
			<?php if(!$forumdata->public) echo Html::img( \Yii::$app->controller->module->getRegisteredImage('private.png'), ['alt'=>'private',  'style'=>'vertical-align:middle;', 'title'=>'Private']); ?>
			<?php if($forumdata->locked) echo Html::img( \Yii::$app->controller->module->getRegisteredImage('locked.png',  ['alt'=>'locked', 'style'=>'vertical-align:middle;', 'title'=>'Locked'])); ?>
			<?php if($forumdata->moderated) echo Html::img( \Yii::$app->controller->module->getRegisteredImage('moderated.png',  ['alt'=>'moderated', 'style'=>'vertical-align:middle;', 'title'=>'Moderated'])); ?>
		</div>
        
	</div> 
</div>
 

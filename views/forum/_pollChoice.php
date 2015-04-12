<?php
/* @var $this ForumController */
/* @var $model YBoardChoice */

use yii\helpers\Html;
?>
<div class="row">
    <div class="poll col-md-12">
        <?php if($this->context->poll->allow_multiple): ?>
            <?php echo Html::checkBox('choice['.$model->id.']', false, array('value'=>$model->id)); ?>
        <?php else: ?>
            <?php echo Html::radio('choice[]', false, array('value'=>$model->id)); ?>
        <?php endif;?>
        <?php echo $model->choice; ?>
    </div>
</div>

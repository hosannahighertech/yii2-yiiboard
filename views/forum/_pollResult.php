<?php
/* @var $this ForumController */
/* @var $model YBoardChoice */
$percentage = ($this->context->poll->votes)?(($model->votes/$this->context->poll->votes)*100):0;
$percentage = round($percentage);

use yii\jui\ProgressBar;
?>

<div class="poll">
    <?php echo $model->choice . ' (' . $model->votes . ' ' . YBoard::t('yboard','votes') . ' ' . $percentage . '%)'; ?>
    <?= ProgressBar::widget([
        'class'=>'poll-progress',
        'clientOptions' => [
            'value' => $percentage,
        ],
    ]) ?>
</div>



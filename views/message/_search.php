<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardMessageSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="yboard-message-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sendfrom') ?>

    <?= $form->field($model, 'sendto') ?>

    <?= $form->field($model, 'subject') ?>

    <?= $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'read_indicator') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'inbox') ?>

    <?php // echo $form->field($model, 'outbox') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'post_id') ?>

    <div class="form-group">
        <?= Html::submitButton(YBoard::t('yboard', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(YBoard::t('yboard', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

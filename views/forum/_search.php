<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardForumSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="yboard-forum-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cat_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'subtitle') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'public') ?>

    <?php // echo $form->field($model, 'locked') ?>

    <?php // echo $form->field($model, 'moderated') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'num_posts') ?>

    <?php // echo $form->field($model, 'num_topics') ?>

    <?php // echo $form->field($model, 'last_post_id') ?>

    <?php // echo $form->field($model, 'poll') ?>

    <?php // echo $form->field($model, 'membergroup_id') ?>

    <div class="form-group">
        <?= Html::submitButton(YBoard::t('yboard', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(YBoard::t('yboard', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

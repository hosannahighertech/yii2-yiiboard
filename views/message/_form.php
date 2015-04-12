<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardMessage $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="yboard-message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sendfrom')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'sendto')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => 39]) ?>

    <?= $form->field($model, 'read_indicator')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'inbox')->textInput() ?>

    <?= $form->field($model, 'outbox')->textInput() ?>

    <?= $form->field($model, 'post_id')->textInput(['maxlength' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? YBoard::t('yboard', 'Create') : YBoard::t('yboard', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

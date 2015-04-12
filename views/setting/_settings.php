<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\yboard\models\YBoardSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="yboard-setting-form-box">

    <?php $form = ActiveForm::begin([
        'id'=>'yboard-setting-form',
    ]); ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => 50, 'id'=>'yboard-setting-key']) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => 100, 'id'=>'yboard-setting-value']) ?> 

    <?php ActiveForm::end(); ?>

</div>

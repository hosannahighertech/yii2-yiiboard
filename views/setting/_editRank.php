<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\yboard\models\YBoardRank */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="yboard-rank-form">

    <?php $form = ActiveForm::begin([
        'id'=>'edit-rank-form',
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'id'=>'YBoardRank_title']) ?>

    <?= $form->field($model, 'min_posts')->textInput(['id'=>'YBoardRank_min_posts']) ?>

    <?= $form->field($model, 'stars')->textInput(['id'=>'YBoardRank_stars']) ?>

    <?= Html::activeHiddenInput($model,'id', ['id'=>'YBoardRank_id']) ?>
    
    <?php ActiveForm::end(); ?>

</div>

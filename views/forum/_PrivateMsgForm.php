
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ckeditor\CKEditor;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardMessage $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="yboard-message-form">

    <?php $form = ActiveForm::begin([
        'id'=>'pm-form',
    ]); ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => 255]) ?>

    <?= CKEditor::widget([
        'model' => $model,
        'attribute'=>'content',
        'id'=>'pmEditor',
    ]) ?> 
  
    <?= Html::hiddenInput('url', \Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/message/create'), ['id'=>'pm-url']) ?>
    

    <?= Html::activeHiddenInput($model, 'sendfrom') ?> 
    
    <?= Html::activeHiddenInput($model, 'sendto', ['id'=>'YBoardMessage_send_to']) ?> 
    
    <?php ActiveForm::end(); ?>

</div>

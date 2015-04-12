<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\Dialog; 
use yii\web\JsExpression; 
use yii\jui\AutoComplete;

use app\modules\yboard\models\YBoardMessage;
use app\components\ckeditor\CKEditor;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

use hosanna\profile\models\User;
use app\modules\yboard\YBoard;


$model= new YBoardMessage(['sendfrom'=>Yii::$app->user->identity->id]);

$data = User::find()
->select(['username'])
->where('id<>'.Yii::$app->user->id)
->asArray()
->all(); 
//format them for Input Widget
$data = ArrayHelper::getColumn($data, 'username');

Dialog::begin([
    'id'=>'dlgPrivateMsg',
    'clientOptions' => [
        'modal' => true,
        'title'=>YBoard::t('yboard', 'Send Private Message'),
        'autoOpen'=>false,
        'height'=>'400',
        'width'=>'400',  
        'buttons'=>[
            [
                'text'=>YBoard::t('yboard', 'Send'), 
                'class'=>'btn btn-sm btn-success', 
                'click'=>new \yii\web\JsExpression('function(){ 
                    for(instance in CKEDITOR.instances)
                    {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    sendPMForm(); 
                }')
            ],		
            [
                'text'=>YBoard::t('yboard', 'Cancel'), 
                'class'=>'btn btn-sm btn-danger', 
                'click'=>new \yii\web\JsExpression(' function() { $( this ).dialog( "close" ); }')
            ]			
        ],
    ],
]);
?>

    <div class="yboard-message-form">

    <?php $form = ActiveForm::begin([
        'id'=>'pm-form',
    ]); ?>
          
    <?= $form->field($model, 'usernames')->widget(Select2::classname(), [        
        'name' => 'usernames', 
        'options' => [
            'placeholder' => 'Select User',
            'id' => 'YBoardMessage_pm_usernames',
        ],
        'pluginOptions' => [ 
            'tags' => $data,
            'maximumInputLength' => 10
        ],
    ]) ?>
       
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


<?php Dialog::end(); ?> 

<?php
/* @var $this SettingController */
/* @var $model YBoardMembergroup */
/* @var $form ActiveForm */

use app\modules\yboard\widgets\picker\ColorPicker;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\Html;
use kartik\widgets\Select2;
use app\modules\yboard\YBoard;

$authMgr = Yii::$app->authManager;
$roles = $authMgr->getRoles();
//format roles title to be capitalized one
$data = [];
foreach($roles as $role)
{
    $data[$role->name] = ucfirst($role->name);
}

 $form = \yii\widgets\ActiveForm::begin([
    'id'=>'edit-membergroup-form',
    'enableAjaxValidation'=>true,
]); ?>

<p class="note"><?php echo YBoard::t('yboard', 'Fields with <span class="required">*</span> are required.'); ?></p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->field($model,'name')->textInput(['id'=>'YBoardMembergroup_name']); ?>

<?php echo $form->field($model,'group_role')->dropDownList($data, ['id'=>'YBoardMembergroup_group_role']); ?>

<?php echo $form->field($model,'description')->textInput(['id'=>'YBoardMembergroup_description']); ?>

<?= ColorPicker::widget([
    'model'=>$model,
    'id'=>'YBoardMembergroup_color',
    'attribute'=>'color',
    'options'=>[ 
        'showInput'=>true,  
        'showPalette'=>true,  
        'color'=>'#ffffff',
        'preferredFormat'=>'hex',
    ]
]) ?>

<?php //echo $form->field($model,'image')->textInput(['id'=>'YBoardMembergroup_image']); 
$url = \Yii::$app->controller->module->getRegisteredImage('/groups/');
$format = <<< SCRIPT
function format(image) { 
    if (!image.id) return image.text; // optgroup
    src = '$url' + image.text.toLowerCase();
    return '<img class="flag" src="' + src + '"/>' ; 
}
SCRIPT;

    $escape = new JsExpression("function(m) { return m; }");
    $this->registerJs($format, View::POS_HEAD);
    
    echo $form->field($model,'image')->widget(Select2::classname(),[
        'data' =>  $this->context->module->getGroupImages(),
        'id'=>'YBoardMembergroup_image',        
        'pluginOptions' => [
            'formatResult' => new JsExpression('format'),
            'formatSelection' => new JsExpression('format'),
            'escapeMarkup' => $escape,
            //'allowClear' => true
        ],
    ]);
?>

<div class="form-group">
    <?php 
        echo Html::activeHiddenInput($model,'id', ['id'=>'YBoardMembergroup_id']);  
    ?>
</div>

<?php \yii\widgets\ActiveForm::end(); ?>

<?php
/* @var $this SettingController */
/* @var $model YBoardForum */
/* @var $category[] YBoardForum  */

use app\modules\yboard\YBoard;
use \app\modules\yboard\models\YBoardForum;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\jui\Sortable;
use yii\web\JsExpression;

$this->title = YBoard::t('yboard', 'Forum Settings');

$this->params['adminMenu'] = [
	['label'=>YBoard::t('yboard', 'Settings'), 'url'=>['setting/index']],
	['label'=>YBoard::t('yboard', 'Member groups'), 'url'=>['setting/group']],
	['label'=>YBoard::t('yboard', 'Moderators'), 'url'=>['setting/moderator']],
	['label'=>YBoard::t('yboard', 'Webspiders'), 'url'=>['setting/spider']],
];

$this->registerJs(\yii\web\View::POS_BEGIN, "
var confirmation = new Array();
confirmation[0] = '" . YBoard::t('yboard', 'Are you sure that you want to delete this category?') . "';
confirmation[1] = '" . YBoard::t('yboard', 'Are you sure that you want to delete this forum?') . "';
", 'confirmationjs');

$this->params['breadcrumbs'] = [
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	['label'=>YBoard::t('yboard', 'settings'), 'url'=>['index']],
	YBoard::t('yboard', 'Manage Forums'),
];

?>
<div id="yboard-wrapper">  
	<p class="header3"><?php echo YBoard::t('yboard', 'Add category or forum'); ?></p>
	
	<div class="form row"> 
        <?php $form = \yii\widgets\ActiveForm::begin([
            'id'=>'yboard-forum-form',
            'enableAjaxValidation'=>false,
        ]); ?>

            <p class="note"><?php echo YBoard::t('yboard', 'Fields with <span class="required">*</span> are required.'); ?></p>
            
            <?php echo $form->errorSummary($model); ?>
            
                <?php echo $form->field($model,'name'); ?>
            
                <?php echo $form->field($model,'subtitle')->textArea(); ?>
            
                <?php echo $form->field($model,'type')->dropDownList(['0'=>YBoard::t('yboard', 'Category'),'1'=>YBoard::t('yboard', 'Forum')], ['id'=>'type']); ?>
            
                <?php echo $form->field($model,'cat_id')->dropDownList(ArrayHelper::map(YBoardForum::find()->categoriesScope()->all(), 'id', 'name'),['prompt'=>'']); ?>
            
                <div class="pad5-left">
                    <?php echo Html::submitButton(YBoard::t('yboard', 'Add'), ['class'=>'btn btn-primary btn-md'] ); ?>
                </div>
                
        <?php \yii\widgets\ActiveForm::end(); ?>	
	</div><!-- form -->
    
    <div class="spacer"></div>
    
    <div class="row"> 
        <div class="sortable">
            <?php
                $items = [];
                foreach($category as $data) {
                    $forum = YBoardForum::find()
                    ->sortedScope()
                    ->forumScope()
                    ->andWhere(['cat_id' => $data->id])
                    ->all();
                    if($data!==null)
                        $items['cat_'.$data->id] = $this->render('_category', ['data'=>$data, 'forum'=>$forum], true);
                }
                echo Sortable::widget([
                    'id' => 'sortcategory',
                    'items' => $items,
                    'options'=>['style'=>'list-style:none; margin-top:1px'],
                    'clientOptions'=>[
                        'delay'=>'100',
                        'update'=>new JsExpression('function(){Sort(this,"' . \Yii::$app->urlManager->createAbsoluteUrl('setting/ajax-sort') . '");}'),
                    ],
                ]);
            ?>
        </div> 
	</div> 		
    
    <div class="spacer"></div>
    
</div>

<?php
 \yii\jui\Dialog::begin([
    'id'=>'dlgEditForum',
    'clientOptions'=>[
        'title'=>'Edit',
        'autoOpen'=>false,
		'modal'=>true,
		'width'=>400,
		'height'=>400,
		'show'=>'fade',
		'buttons'=>[
			YBoard::t('yboard', 'Delete')=>new JsExpression('function(){ deleteForum("' . Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/delete-forum') .'"); }'),
			YBoard::t('yboard', 'Save')=>new JsExpression('function(){ saveForum("' . Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/save-forum') .'"); }'),
			YBoard::t('yboard', 'Cancel')=>new JsExpression('function(){ $(this).dialog("close"); }'),
		], 
    ],
]);

    echo $this->render('_editForum', ['model'=>$model]);

\yii\jui\Dialog::end();;
?>

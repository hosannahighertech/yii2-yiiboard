<?php
use Yii;
use yii\helpers\Html;
use yii\web\JsExpression;
use app\modules\yboard\YBoard;

 
  
/* @var $this SettingController */
/* @var $model YBoardMembergroup */
 
$this->title = YBoard::t('yboard', 'Member Groups');

$this->params['breadcrumbs'] = [	
    ['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	['label'=>YBoard::t('yboard', 'settings'), 'url'=>['index']],
	YBoard::t('yboard', 'Member groups')
];

$this->params['adminMenu'] = [
	['label'=>YBoard::t('yboard', 'Settings'), 'url'=>['setting/index']],
	['label'=>YBoard::t('yboard', 'Manage forums'), 'url'=>['setting/forum']],
	['label'=>YBoard::t('yboard', 'Ranks'), 'url'=>['setting/rank']],
	['label'=>YBoard::t('yboard', 'Moderators'), 'url'=>['setting/moderator']],
	['label'=>YBoard::t('yboard', 'Webspiders'), 'url'=>['setting/spider']],
];

$this->registerJs("
var confirmation = '" . YBoard::t('yboard', 'Are you sure that you want to delete this member group?') . "'
", \yii\web\View::POS_BEGIN, 'confirmation');
?>

<div id="yboard-wrapper">
	<div class="pad5-bottom">
        <?php echo Html::button(YBoard::t('yboard', 'New Group').' <span class="glyphicon glyphicon-user"></span>', ['onclick'=>'editMembergroup()', 'class'=>'btn btn-primary btn-sm']); ?>
    </div>
	
	<?= \yii\grid\GridView::widget([
		'id'=>'membergroup-grid',
		'layout'=>'{items} {summary} {pager}',
		'dataProvider'=>$model->search(), 
 		'columns'=>[
             ['class' => 'yii\grid\SerialColumn'],
 			//'id',
			'name',
			'description', 
			[
				'attribute' => 'color', 
				'format' => 'html', 
				'value' => function ($data){  
                    return Html::tag('p', $data->color); 
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                     return ['style'=>"font-weight:bold;color:".$model->color.";"];
                }
			],
			[
				'attribute' => 'image', 
				'format' => 'html', 
				'value' => function ($data){  
                    return Html::img($this->context->module->getRegisteredImage('groups/'.$data->image), ['alt'=>$data->image]); 
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                     return ['style'=>"color:".$model->color.";"];
                }
			], 
			'group_role',
			[
				'class' => 'yii\grid\ActionColumn',
				'template'=>'{update}',
				'buttons' => [
					'update' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                            'onclick'=>'editMembergroup('.$model->id.', "' . Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/setting/get-membergroup') .'"); return false;',
                        ]);
                        
                    }, 
				] 
			],
		],
	])  ?>
</div>

<?php
\yii\jui\Dialog::begin([
    'id'=>'dlgEditMembergroup', 
    'clientOptions'=>[
        'title'=>'Member Group',
        'autoOpen'=>false,
		'modal'=>true,
		'width'=>400,
		'show'=>'fade',
		'buttons'=>[ 
			YBoard::t('yboard', 'Delete')=>new JsExpression('function(){ deleteMembergroup("' . Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/delete-membergroup') .'"); }'),
			YBoard::t('yboard', 'Save')=>new JsExpression('function(){ saveMembergroup("' . Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/save-membergroup') .'"); }'),
			YBoard::t('yboard', 'Cancel')=>new JsExpression('function(){ $(this).dialog("close"); }'),
		],
    ],
]);

    echo $this->render('_editMembergroup',['model'=>$model]);

\yii\jui\Dialog::end();;
?>

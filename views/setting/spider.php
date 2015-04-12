<?php
/* @var $this SettingController */
/* @var $model YBoardSpider */
 
use yii\helpers\Html;
use yii\web\View;
use yii\web\JsExpression;
use yii\grid\GridView;
use yii\jui\Dialog;
use app\modules\yboard\YBoard;
use app\modules\yboard\components\DateTimeCalculation;
 

$this->params['breadcrumbs'] = [
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
    ['label'=>YBoard::t('yboard', 'Settings'), 'url'=>['setting/index']],
	YBoard::t('yboard', 'Web Spiders')
];

$this->title = YBoard::t('yboard', 'Settings - Spiders');

$items = array(
	['label'=>YBoard::t('yboard', 'Settings'), 'url'=>array('setting/index')],
	['label'=>YBoard::t('yboard', 'Manage forums'), 'url'=>array('setting/forum')],
	['label'=>YBoard::t('yboard', 'Member groups'), 'url'=>array('setting/group')],
	['label'=>YBoard::t('yboard', 'Moderators'), 'url'=>array('setting/moderator')]
); 

$this->registerJs("
    var confirmation = '" . YBoard::t('yboard', 'Are you sure that you want to delete this webspider?') . "'
", View::POS_HEAD);
?>
<div id="yboard-wrapper"  class="container">
	
	<p class="pad5"><?= Html::button(YBoard::t('yboard', 'New Spider'), array('onclick'=>'YBoardSetting.EditSpider()', 'class'=>'btn btn-primary btn-sm')) ?></p>
	 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], 
            'name',
            'user_agent',
            'hits',
            [
                'attribute'=>'last_visit',
                'value'=>function($data){ 
                    return DateTimeCalculation::medium($data->last_visit); 
                }, 
            ],

            [
				'class' => 'yii\grid\ActionColumn',
				'template'=>'{update}',
				'buttons' => [
					'update' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                            'onclick'=>'YBoardSetting.EditSpider('. $model->id.',"'.Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/get-spider').'"); return false;',
                        ]);
                        
                    }, 
				] 
			],
        ],
    ]); ?>      
     
 </div>

<?php
Dialog::begin([
    'id'=>'dlgEditSpider', 
    'clientOptions'=>[
        'title'=>'Web Spider',
        'autoOpen'=>false,
		'modal'=>true,
		'width'=>400,
		'show'=>'fade',
		'buttons'=>[ 
			YBoard::t('yboard', 'Delete')=>new JsExpression('function(){ YBoardSetting.DeleteSpider("' . Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/delete-spider') .'"); }'),
			YBoard::t('yboard', 'Save')=>new JsExpression('function(){ YBoardSetting.SaveSpider("' . Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/save-spider') .'"); }'),
			YBoard::t('yboard', 'Cancel')=>new JsExpression('function(){ $(this).dialog("close"); }'),
		],
    ],
]);

    echo $this->render('_editSpider',['model'=>$model]);

Dialog::end(); 
 

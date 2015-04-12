<?php
/* @var $this ForumController */
/* @var $model YBoardSetting */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
use app\modules\yboard\YBoard;
use app\modules\yboard\models\YBoardSetting;
 
$this->params['breadcrumbs'] = [
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	YBoard::t('yboard', 'Settings'),
];


$this->title = YBoard::t('yboard', 'Settings');

$this->params['adminMenu']  = [
	['label'=>YBoard::t('yboard', 'Manage Forum'), 'url'=>['setting/forum']],
	['label'=>YBoard::t('yboard', 'Member groups'), 'url'=>['setting/group']],
	['label'=>YBoard::t('yboard', 'Manage Member'), 'url'=>['setting/users']],
	['label'=>YBoard::t('yboard', 'Permissions'), 'url'=>['/admin']],
	['label'=>YBoard::t('yboard', 'Webspiders'), 'url'=>['setting/spider']],
];
?>
<div id="yboard-wrapper">

    <div class="row">
        <div class="pad5">
            <?= Html::button(YBoard::t('yboard', 'New').' <span class="glyphicon glyphicon-cog"></span>', ['class'=>'btn btn-primary btn-sm', 'onclick'=>'newSetting("'.Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/create').'");']) ?>
         </div>
    </div>
    
    <div class="row">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'=>"\n{items}\n{pager}",        
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                'key',
                'value', 
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete}',
                    'buttons'=>[
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('yii', 'Update'),
                                'data-pjax' => '0',
                                'onclick'=>new JsExpression('updateSetting("'.$url.'","'.$model->key.'","'.$model->value.'"); return false;')
                            ]);
                        }, 
                        
                    ]
                ],
            ],
        ]); ?>
	</div> 
</div>

<?php
 \yii\jui\Dialog::begin([
    'id'=>'dlgNewSetting',
    'clientOptions'=>[
        'title'=>YBoard::t('yboard', 'New Setting'),
        'autoOpen'=>false,
		'modal'=>true,
		'width'=>300,
		'height'=>300,
		'show'=>'fade',
		'buttons'=>[
			YBoard::t('yboard', 'Save')=>new JsExpression('function(){ saveSetting("' . Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/create') .'"); }'),
			YBoard::t('yboard', 'Cancel')=>new JsExpression('function(){ $(this).dialog("close"); }'),
		], 
    ],
]);

    echo $this->render('_settings', ['model'=>new YBoardSetting]);

\yii\jui\Dialog::end();;
?>


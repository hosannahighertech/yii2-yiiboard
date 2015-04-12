<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
use app\modules\yboard\models\YBoardRank;
use app\modules\yboard\YBoard;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = YBoard::t('yboard', 'Member Ranks');

$this->params['breadcrumbs'] = [	
    ['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	['label'=>YBoard::t('yboard', 'settings'), 'url'=>['index']],
	YBoard::t('yboard', 'Member ranks')
];

$this->params['adminMenu'] = [
	['label'=>YBoard::t('yboard', 'Settings'), 'url'=>['setting/index']],
	['label'=>YBoard::t('yboard', 'Manage forums'), 'url'=>['setting/forum']],
	['label'=>YBoard::t('yboard', 'Manage Members'), 'url'=>['setting/group']],
	['label'=>YBoard::t('yboard', 'Webspiders'), 'url'=>['setting/spider']],
];


$this->registerJs("
var confirmation = '" . YBoard::t('yboard', 'Are you sure that you want to delete this member group?') . "'
", \yii\web\View::POS_BEGIN, 'confirmation');


?>

<div class="yboard-rank-index">
	<div class="pad5">
        <?php echo Html::button(YBoard::t('yboard', 'New Rank').' <span class="glyphicon glyphicon-user"></span>', ['onclick'=>'editRank()', 'class'=>'btn btn-primary btn-sm']); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], 
            'id',
            'title',
            'min_posts',
            'stars',

            [
				'class' => 'yii\grid\ActionColumn',
				'template'=>'{update}',
				'buttons' => [
					'update' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                            'onclick'=>'editRank('.$model->id.', "' . Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/setting/get-rank') .'"); return false;',
                        ]);
                        
                    }, 
				] 
			],
        ],
    ]); ?>

</div>


<?php
\yii\jui\Dialog::begin([
    'id'=>'dlgEditRank', 
    'clientOptions'=>[
        'title'=>'Member Group',
        'autoOpen'=>false,
		'modal'=>true,
		'width'=>400,
		'show'=>'fade',
		'buttons'=>[ 
			YBoard::t('yboard', 'Delete')=>new JsExpression('function(){ deleteRank("' . Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/delete-rank') .'"); }'),
			YBoard::t('yboard', 'Save')=>new JsExpression('function(){ saveRank("' . Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/save-rank') .'"); }'),
			YBoard::t('yboard', 'Cancel')=>new JsExpression('function(){ $(this).dialog("close"); }'),
		],
    ],
]);

    echo $this->render('_editRank',['model'=>new YBoardRank]);

\yii\jui\Dialog::end();;
?>


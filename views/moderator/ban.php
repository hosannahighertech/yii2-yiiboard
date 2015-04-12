<?php

use yii\jui\Dialog;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\yboard\YBoard;
use app\modules\yboard\components\DateTimeCalculation;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\yboard\models\YBoardBanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = YBoard::t('yboard', 'Manage Bans');

$this->params['breadcrumbs'][] = ['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']];
$this->params['breadcrumbs'][] = ['label'=>YBoard::t('yboard', 'Mod CP'), 'url'=>['moderator/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="yboard-ban-index">

    <p class="header2"><?= Html::encode($this->title) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], 
            [
                'attribute' => 'user_id', 
                'value' => function($model, $index, $dataColumn) {
                    return $model->member->profile->username;
                },
            ],
            [
                'attribute' => 'banned_by', 
                'value' => function($model, $index, $dataColumn) {
                    return $model->banner->profile->username;
                },
            ],
            [
                'attribute'=>'expires',
                'format' => 'raw', 
                'value'=>function($model, $index, $dataColumn){
                    return Editable::widget([
                        'value' => DateTimeCalculation::short($model->expires),
                        'name' => 'expires',
                        'options' => ['name' => 'expires'],  // unique input name
                        'format' => Editable::FORMAT_BUTTON,
                        'inputType' => Editable::INPUT_DATETIME, 
                        'formOptions'=>[
                            'action'=>url::to(['moderator/change-ban-period', 'id'=>$model->id])
                        ],
                    ]); 
                }            
            ],

            'ip',
            'email:email', 
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{lift} {reason}',
                'buttons'=>[
                    'lift' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-tint"></span>', '#', [
                            'title'=>YBoard::t('yboard', 'Lift Ban'),
                                'onclick'=>'if(confirm("' . YBoard::t('yboard','Do you really Lift this Ban') . '")) { banLift("' . Yii::$app->urlManager->createAbsoluteUrl([$this->context->module->id.'/moderator/ban-lift', 'id'=>$model->id]) . '"); }return false; ',
                        ]);
                    },
                    'reason' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-envelope"></span>', '#', [
                            'title'=>YBoard::t('yboard', 'Ban Reason'),
                            'onclick'=>'banMessage(\''.$model->message.'\')'
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>

</div>

<?php
Dialog::begin([
    'id'=>'dlgBanMsg',
    'clientOptions' => [
        'modal' => true,
        'title'=>YBoard::t('yboard', 'Ban Reason'),
        'autoOpen'=>false,
        'modal'=>true,   
        'height'=>'auto',
        'min-height'=>100,
        'width'=>200,  
        'buttons'=> [ 
            [
                'text'=>YBoard::t('yboard', 'close'), 
                'class'=>'btn btn-sm btn-danger', 
                'click'=>new \yii\web\JsExpression(' function() { $( this ).dialog( "close" ); }')
            ]
        ],
    ],
]); ?>
    
    <div id="dlgBanMsgText" class="alert alert-warning panel"></div>
    
<?php Dialog::end(); ?> 

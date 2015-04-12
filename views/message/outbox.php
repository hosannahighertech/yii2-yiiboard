<?php
/* @var $this MessageController */
/* @var $model YBoardMessage */
/* @var $count Array */


use yii\helpers\Html;
use app\modules\yboard\YBoard;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\JsExpression;
use app\modules\yboard\components\DateTimeCalculation;
 

$this->params['breadcrumbs']=[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']], 
	['label'=>YBoard::t('yboard', 'Inbox') .' ('. $count['inbox'] .')', 'url'=>['message/inbox', 'id'=>Yii::$app->user->id]],
    YBoard::t('yboard', 'Outbox'),
];


$this->title = YBoard::t('yboard', 'Messages- Outbox');

?>
<div id="yboard-wrapper" class="container">
	<?= $this->render('_pmDialog') ?>
	
	<div class="progress"><div class="progressbar" style="width:<?php echo (2*$count['outbox']); ?>%"> </div></div>
     
	<div id="yboard-message"></div><br>
    
<?php \yii\widgets\Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
		'id'=>'outbox-grid-box',
        'rowOptions' => function ($model, $index, $widget, $grid){
            return ['class'=>$model->read_indicator?'':'unread'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [                
				'attribute'=>'sendto',
				'value'=>function ($model, $key, $index, $column)
                { 
                    return $model->receiver==null ? YBoard::t('yboard', 'Management') : $model->receiver->profile->username;
                }
            ],
            'subject',
            [                
				'attribute'=>'create_time',
				'value'=>function ($model, $key, $index, $column)
                { 
                    return DateTimeCalculation::long($model->create_time);
                }
            ], 
            [                
				'attribute'=>'type',
				'value'=>function ($model, $key, $index, $column)
                { 
                    return ($model->type)?Yii::t("app", "notification"):Yii::t("app", "message");
                }
            ],
            
            [
                'class' => 'yii\grid\ActionColumn',
				'template'=>'{view}{delete}',
                'buttons'=>[
                    'view'=>function ($url, $model) {
                        // return the button HTML code
                        $src = $this->context->module->getRegisteredImage('view.png');
                        return Html::a(Html::img($src, ['alt'=>'view']),$model->id , [
                            'style'=>'margin-left:5px;',
                            'onclick'=>new JsExpression('
                                viewMessage($(this).attr("href"), "' . Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/message/view') .'");
                                return false; 
                                '
                            ),
                        ]);  
                    },                    
                    'delete'=>function ($url, $model) {
                        // return the button HTML code
                        $src = $this->context->module->getRegisteredImage('delete.png'); 
                        return Html::a(Html::img($src, ['alt'=>'view']), '#', [
                            'style'=>'margin-left:5px;',
                            'onclick'=>new \yii\web\JsExpression('
                                what = confirm("'.Yii::t('yii', 'Are you sure you want to delete this item?').'");
                                if(what)
                                {
                                    //delete with ajax
                                    $.ajax({
                                        type: "POST",
                                        url: "'.$url.'", 
                                    }).done(function(data) {
                                        data = JSON.parse(data); 
                                        if(data["success"])
                                            $.pjax.reload({container:"#outbox-grid-box"});
                                    });
                                }
                            '),
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>  
<?php \yii\widgets\Pjax::end(); ?>

</div>

<?php
/* @var $this MessageController */
/* @var $model YBoardMessage */
/* @var $count Array */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\JsExpression;
use app\modules\yboard\YBoard;
use app\modules\yboard\components\DateTimeCalculation;


$this->params['breadcrumbs']=[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']], 
	['label'=>YBoard::t('yboard', 'Outbox') .' ('. $count['outbox'] .')', 'url'=>['message/outbox', 'id'=>Yii::$app->user->id]],
    YBoard::t('yboard', 'Inbox'),
];


$this->title = YBoard::t('yboard', 'Messages - Inbox');

?>
<div id="yboard-wrapper" class="container">
	<?= $this->render('_pmDialog') ?>
    
	<div class="progress"><div class="progressbar" style="width:<?php echo ($count['inbox'] < 100)?$count['inbox']:100; ?>%"> </div></div>

	<div id="yboard-message"></div><br>
    <p class="pull-right pad5-right"><?= 	Html::button(YBoard::t('yboard', 'New message'), ['class'=>'btn btn-default btn-md', 'style'=>'cursor:pointer;', 'onclick'=>'sendPm('.Yii::$app->user->id.'); return false;']) ?></p>
 
    <?php \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
		'id'=>'inbox-grid-box',
        'rowOptions' => function ($model, $index, $widget, $grid){
            return ['class'=>$model->read_indicator?'':'unread', 'id'=>'msg_id_'.$model->id];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [                
				'attribute'=>'sendfrom',
				'value'=>function ($model, $key, $index, $column)
                { 
                    return $model->sender->profile->username;
                }
            ],
            'subject',
            [                
				'attribute'=>'create_time',
				'value'=>function ($model, $key, $index, $column)
                { 
                    return DateTimeCalculation::short($model->create_time);
                }
            ],              
            [
                'class' => 'yii\grid\ActionColumn',
				'template'=>'{view}{reply}{delete}',
                'buttons'=>[
                    'view'=>function ($url, $model) {
                        // return the button HTML code
                        $src = $this->context->module->getRegisteredImage('view.png');
                        return Html::a(Html::img($src, ['alt'=>'view']), '#' , [
                            'style'=>'margin-left:5px;',
                            'onclick'=>new JsExpression('
                                viewMessage("'.$model->id.'", "' . Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/message/view', ['id'=>$model->id]) .'");
                                return false; 
                                '
                            ),
                        ]);  
                    },
                    'reply'=>function ($url, $model) {
                        // return the button HTML code
                        $src = $this->context->module->getRegisteredImage('reply.png');
                        return Html::a(Html::img($src, ['alt'=>'reply']), '#', [
                            'style'=>'margin-left:5px;',
                            'onclick'=>new JsExpression('
                                replyMessage('.$model->sendfrom.')
                                return false; 
                                '
                            ),
                            
                        ]).' ';
                    },
                    'delete'=>function ($url, $model) {
                        // return the button HTML code
                        $src = $this->context->module->getRegisteredImage('delete.png'); 
                        return Html::a(Html::img($src, ['alt'=>'view']), '#', [
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
                                            $.pjax.reload({container:"#inbox-grid-box"});
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

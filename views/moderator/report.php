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


$this->title = YBoard::t('yboard', 'Reported Posts');

$this->params['breadcrumbs']=[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']], 
	['label'=>YBoard::t('yboard', 'Mod CP'), 'url'=>['moderator/index']],
    $this->title,
]; 

?>
<div id="yboard-wrapper" class="container">
    
	<div id="yboard-message"></div><br>
 
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
				'template'=>'{view} {delete}',
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
                    'delete'=>function ($url, $model) {
                        $url = Yii::$app->urlManager->createAbsoluteUrl([$this->context->module->id.'/message/delete', 'id'=>$model->id]);
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

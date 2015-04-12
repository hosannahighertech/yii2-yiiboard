<?php
/* @var $this ForumController */
/* @var $model YBoardSetting */

use yii\jui\Dialog;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\JsExpression;
use app\modules\yboard\models\YBoardTopic;
use app\modules\yboard\models\YBoardSetting;
use app\modules\yboard\YBoard; 

$this->title = YBoard::t('yboard', 'Approve Posts and Topics');

$this->params['breadcrumbs'][] = ['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']];
$this->params['breadcrumbs'][] = ['label'=>YBoard::t('yboard', 'Mod CP'), 'url'=>['moderator/index']];
$this->params['breadcrumbs'][] = $this->title;
 
?>

<div id="yboard-wrapper">
    <div class="row">  
        <div class="col-md-12"> 
            <ul class="list-group">
                <li class="header2 header2-style"><?= YBoard::t('yboard', 'Pending Topics') ?></li>
            
                <?= ListView::widget([ 
                    'dataProvider' => $topicsProvider,
                    'layout' => '{items}{pager}',
                    'itemView' => function ($model, $key, $index, $widget){
                        return '<li class="list-group-item" id="id_topic_'.$model->id.'"><div class="row">
                            <div class="col-md-4"><span class="header3">'.$model->title.'</span></div> '
                                .'<div class="col-md-8">'
                                    .Html::button(YBoard::t('yboard', 'approve'), ['onclick'=>'YBoard.topicApprove("id_topic_'.$model->id.'", "'.Yii::$app->urlManager->createAbsoluteUrl([$this->context->module->id.'/moderator/approve-topic', 'id'=>$model->id]) .'")', 'class'=>'btn btn-success btn-sm'])
                                    .' '
                                    .Html::button(YBoard::t('yboard', 'edit'), ['class'=>'btn btn-info btn-sm', 'onclick'=>'YBoard.updateTopic(' . $model->id . ', "' . \Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/moderator/topic') . '")']) 
                                    
                                .'</div>'
                            .'</div></li>';
                    },
                    'itemOptions' => [
                    ], 
                ]) ?> 
            </ul>
        </div>
	</div> 
    
    <div class="row">  
        <div class="col-md-12"> 
            <ul class="list-group">
                <li class="header2 header2-style"><?= YBoard::t('yboard', 'Pending Posts') ?></li>
                
                <?= ListView::widget([ 
                    'dataProvider' => $postsProvider,
                    'layout' => '{items}{pager}',
                    'itemView' => function ($model, $key, $index, $widget){
                        return '<li class="list-group-item" id="id_post_'.$model->id.'"><div class="row">
                            <div class="col-md-4"><span class="header3">'.$model->subject.'</span></div> '
                                .'<div class="col-md-8">'
                                    .Html::button(YBoard::t('yboard', 'view'), ['class'=>'btn btn-default btn-sm', 'onclick'=>'YBoard.viewPost('.json_encode(htmlspecialchars($model->content)).')'])
                                    .' '
                                    .Html::button(YBoard::t('yboard', 'approve'), ['onclick'=>'YBoard.postApprove("id_post_'.$model->id.'", "'.Yii::$app->urlManager->createAbsoluteUrl([$this->context->module->id.'/moderator/approve-post', 'id'=>$model->id]) .'")', 'class'=>'btn btn-success btn-sm'])
                                    .' '
                                    .Html::button(YBoard::t('yboard', 'edit'), ['class'=>'btn btn-info btn-sm', 'onclick'=>'YBoard.postUpdateDialog("'.Yii::$app->urlManager->createAbsoluteUrl([$this->context->module->id.'/moderator/get-post', 'id'=>$model->id]) .'")'])
                                    .' '
                                    .Html::button(YBoard::t('yboard', 'delete'), ['class'=>'btn btn-danger btn-sm', 'onclick'=>'if(confirm("' . YBoard::t('yboard','Do you really want to delete this post?') . '")) { deletePost("' . Yii::$app->urlManager->createAbsoluteUrl([$this->context->module->id.'/moderator/delete', 'id'=>$model->id]) . '"); return false; }'])
                                .'</div>'
                            .'</div></li>';
                    },
                    'itemOptions' => [
                    ], 
                ]) ?> 
            </ul>
        </div>
	</div> 
</div> 

<?php
Dialog::begin([
    'id'=>'dlgapproveMsg',
    'clientOptions' => [
        'modal' => true,
        'title'=>YBoard::t('yboard', 'Text Preview'),
        'autoOpen'=>false,
        'modal'=>true,   
        'height'=>400,
        'width'=>450,  
        'buttons'=> [ 
            [
                'text'=>YBoard::t('yboard', 'close'), 
                'class'=>'btn btn-sm btn-danger', 
                'click'=>new \yii\web\JsExpression(' function() { $( this ).dialog( "close" ); }')
            ]
        ],
    ],
]); ?>
    
    <div id="dlgapproveMsgText" class="alert alert-warning panel"></div>
    
<?php Dialog::end(); ?> 

<?php  
Dialog::begin([
    'id'=>'dlgTopicForm',
    'clientOptions' => [
        'modal' => true,
        'title'=>YBoard::t('yboard', 'Update topic'),
        'autoOpen'=>false,
        'modal'=>true,   
        'height'=>400,
        'width'=>300,  
        'buttons'=> [ 
            [
                'text'=>YBoard::t('yboard', 'Change'), 
                'class'=>'btn btn-sm btn-success', 
                'click'=>new JsExpression(' function() { YBoard.changeTopic("' . \Yii::$app->urlmanager->createAbsoluteUrl($this->context->module->id.'/moderator/change-topic') . '"); }')
            ],
            [
                'text'=>YBoard::t('yboard', 'Cancel'), 
                'class'=>'btn btn-sm btn-danger', 
                'click'=>new JsExpression(' function() { $( this ).dialog( "close" ); }')
            ]
        ],
    ],
]);

    echo $this->render('../forum/_topicForm', ['model'=>new YBoardTopic]);

Dialog::end(); 

//Update Post contents
Dialog::begin([
    'id'=>'dlgUpdatePostForm',
    'clientOptions' => [
        'modal' => true,
        'title'=>YBoard::t('yboard', 'Update topic'),
        'autoOpen'=>false,
        'modal'=>true,   
        'height'=>400,
        'width'=>300,  
        'buttons'=> [ 
            [
                'text'=>YBoard::t('yboard', 'Change'), 
                'class'=>'btn btn-sm btn-success', 
                'click'=>new JsExpression(' function() { YBoard.updateModPost("' . \Yii::$app->urlmanager->createAbsoluteUrl($this->context->module->id.'/moderator/change-post') . '"); }')
            ],
            [
                'text'=>YBoard::t('yboard', 'Cancel'), 
                'class'=>'btn btn-sm btn-danger', 
                'click'=>new JsExpression(' function() { $( this ).dialog( "close" ); }')
            ]
        ],
    ],
]);

    echo '<div id="dlgUpdatePostFormHolder"></div>';

Dialog::end(); 

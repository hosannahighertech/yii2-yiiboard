<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use app\modules\yboard\YBoard;
use app\modules\yboard\components\DateTimeCalculation;

use kartik\editable\Editable;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardMember $model
 */

$this->title = YBoard::t('yboard', 'User CP - {name}', ['name'=>Html::encode($model->profile->fullname)]);
$this->params['breadcrumbs'][] = $this->title;


$this->params['breadcrumbs']=[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
    ['label' => YBoard::t('yboard', 'Members List'), 'url' => ['index']],
    ['label' => Html::encode($model->profile->username), 'url' => ['member/view', 'id'=>$model->id]],
    $this->title
];

?>

<?php
    $birthday = $this->context->module->birthdateColumn;
    $gender = $this->context->module->genderColumn;
    $regdate = $this->context->module->regdateColumn;

    $timezone = isset($model->profile->timezone)? new DateTimeZone($model->profile->timezone) : new DateTimeZone() ;
?>
                
<?php $basicInfo = DetailView::widget([
    'model' => $model,
    'options'=>[
        'class' => 'table table-striped detail-view'
    ],
    'attributes' => [ 
        [
            'label'=>YBoard::t('yboard', 'Birthday'),
            'value'=>$birthday==null?YBoard::t('yboard', 'None'):$model->profile->{$birthday},
        ],    
        
        [
            'label'=>YBoard::t('yboard', 'Gender'),
            'value'=>($model->profile->{$gender}==1?YBoard::t('yboard', 'Male'):YBoard::t('yboard', 'Female'))                        ],    
        [
            'label'=>YBoard::t('yboard', 'Joined'),
            'value'=>$regdate==null?YBoard::t('yboard', 'None'): date_format(date_timezone_set(date_timestamp_set(date_create(), $model->profile->{$regdate}), $timezone), 'l, d-M-y H:i T'),
        ],            
        [
            'attribute'=>'location',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'location',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],         
        [
            'attribute'=>'signature',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'signature',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ], 
        'status',   
    ],
]); ?> 


<?php $foroStatistics =  DetailView::widget([
    'model' => $model,
    'options'=>[
        'class' => 'table table-striped detail-view'
    ],
    'attributes' => [ 
        [
            'label'=>YBoard::t('yboard', 'Last Visit'),
            'value'=>date_format(date_timezone_set(date_create($model->last_visit), $timezone), 'l, d-M-y H:i T'),
        ],   
        'appreciations',   
        'startedTopics', 
        'totalReplies', 
        'recentTopics:html', 
    ],
]); ?>  

 
<?php $webInfo =  DetailView::widget([
    'model' => $model,
    'options'=>[
        'class' => 'table table-striped detail-view'
    ],
    'attributes' => [ 
        [
            'attribute'=>'blogger',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'blogger',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],
        [
            'attribute'=>'contact_email',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'contact_email',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_DROPDOWN_LIST,
                'data'=>[1=>'Allow', 0=>'Forbid'],
                'displayValueConfig'=>[0=>YBoard::t('yboard','Forbidden'), 1=>YBoard::t('yboard','Allowed')],
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
                'pluginEvents' => [
                    "editableSuccess"=>"function(event, val) { 
                        //console.log('Successful submission of value ' + val); 
                    }",
                ],
            ])
        ],  
        [
            'attribute'=>'contact_pm',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'contact_pm',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_DROPDOWN_LIST,
                'data'=>[1=>'Allow', 0=>'Forbid'],
                'displayValueConfig'=>[0=>YBoard::t('yboard','Forbidden'), 1=>YBoard::t('yboard','Allowed')],
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
                'pluginEvents' => [
                    "editableSuccess"=>"function(event, val) { 
                        //console.log('Successful submission of value ' + val); 
                    }",
                ],
            ])
        ],  
        [
            'attribute'=>'facebook',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'facebook',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],  
        [
            'attribute'=>'skype',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'skype',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],
        [
            'attribute'=>'google',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'google',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ], 
        [
            'attribute'=>'linkedin',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'linkedin',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ], 
        [
            'attribute'=>'metacafe',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'metacafe',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ], 
        [
            'attribute'=>'github',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'github',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ], 
        [
            'attribute'=>'orkut',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'orkut',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],  
        [
            'attribute'=>'tumblr',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'tumblr',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],  
        [
            'attribute'=>'twitter',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'twitter',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],
        [
            'attribute'=>'website',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'website',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],
        [
            'attribute'=>'wordpress',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'wordpress',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],
        [
            'attribute'=>'yahoo',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'yahoo',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ],
        [
            'attribute'=>'youtube',
            'format'=>'raw',
            
            'value'=>Editable::widget([
                'model'=>$model, 
                'attribute' => 'youtube',
                'type'=>'primary',
                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_TEXT,
                'editableValueOptions'=>['class'=>'text-success pad15-right'],
                'formOptions'=>[
                    'action'=>url::to(['member/usercp', 'id'=>$model->id])
                ],
            ])
        ], 
    ],
]); ?>  


<div class="yboard-member-view container">         
    <div class="row">
        <div class="col-md-2">
           <div class="center">
               <?= Html::img(isset($model->profile->image)? $model->profile->image:$this->context->module->getRegisteredImage("empty.jpeg"),['id'=>'user-avatar'])  ?>
               <p class="pad5-top"><?= ucfirst($model->profile->{$this->context->module->userNameColumn}) ?></p>
                <?= Html::a(YBoard::t('yboard','Edit Basic Profile'), [$this->context->module->profile['edit'], 'id'=>$model->id], ['class'=>'btn btn-primary btn-md']) ?>
            </div>
        </div>
        
        <div class="col-md-10">
            <?= Tabs::widget([
                'items' => [
                    [
                        'label' => YBoard::t('yboard', 'Basic Information'),
                        'content' => $basicInfo,
                        'active' => true
                    ],
                    [
                        'label' => YBoard::t('yboard', 'Forum Statistics'),
                        'content' => $foroStatistics, 
                    ],  
                    [
                        'label' => YBoard::t('yboard', 'Web Information'),
                        'content' => $webInfo, 
                    ], 
                ],
            ]) ?>
        </div>
</div>

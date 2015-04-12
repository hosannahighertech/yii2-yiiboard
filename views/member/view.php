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
        'location',  
        'signature',   
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
            'visible'=>trim($model->blogger)!="", 
        ],
        [
            'attribute'=>'contact_email',             
            'value'=>$model->contact_email==0 ? YBoard::t('yboard','Forbidden') : YBoard::t('yboard','Allowed'),
        ],  
        [
            'attribute'=>'contact_pm',             
            'value'=>$model->contact_pm==0 ? YBoard::t('yboard','Forbidden') : YBoard::t('yboard','Allowed'),
        ],   
        [
            'attribute'=>'facebook',
            'visible'=>trim($model->facebook)!="", 
        ], 
        [
            'attribute'=>'skype',
            'visible'=>trim($model->skype)!="", 
        ], 
        [
            'attribute'=>'google',
            'visible'=>trim($model->google)!="", 
        ], 
        [
            'attribute'=>'linkedin',
            'visible'=>trim($model->linkedin)!="", 
        ], 
        [
            'attribute'=>'metacafe',
            'visible'=>trim($model->metacafe)!="", 
        ], 
        [
            'attribute'=>'github',
            'visible'=>trim($model->github)!="", 
        ], 
        [
            'attribute'=>'orkut',
            'visible'=>trim($model->github)!="", 
        ], 
        [
            'attribute'=>'orkut',
            'visible'=>trim($model->orkut)!="", 
        ], 
        [
            'attribute'=>'tumblr',
            'visible'=>trim($model->tumblr)!="", 
        ], 
        [
            'attribute'=>'twitter',
            'visible'=>trim($model->twitter)!="", 
        ], 
        [
            'attribute'=>'website',
            'visible'=>trim($model->website)!="", 
        ], 
        [
            'attribute'=>'wordpress',
            'visible'=>trim($model->wordpress)!="", 
        ], 
        [
            'attribute'=>'yahoo',
            'visible'=>trim($model->yahoo)!="", 
        ], 
        [
            'attribute'=>'youtube',
            'visible'=>trim($model->youtube)!="", 
        ], 
    ],
]); ?>  


<div class="yboard-member-view container">         
    <div class="row">
        <div class="col-md-2">
           <div class="center">
               <?= Html::img(isset($model->profile->image)? $model->profile->image:$this->context->module->getRegisteredImage("empty.jpeg"),['id'=>'user-avatar'])  ?>
               <p class="pad5-top"><?= ucfirst($model->profile->{$this->context->module->userNameColumn}) ?></p>
                <?= Html::a(YBoard::t('yboard','View Basic Profile'), [$this->context->module->profile['view'], 'id'=>$model->id], ['class'=>'btn btn-default btn-md']) ?>
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

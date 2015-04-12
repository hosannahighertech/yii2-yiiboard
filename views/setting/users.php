<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use app\modules\yboard\YBoard;
use app\modules\yboard\models\YBoardMessage;
use yii\jui\Dialog;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\yboard\models\YBoardMemberSearch $searchModel
 */ 

$this->title = YBoard::t('yboard', 'Member Profiles');
$this->params['breadcrumbs'][] = $this->title;


$this->params['breadcrumbs']=[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']], 
    $this->title
];

?>
<div id="yboard-member-index" class="container"> 
    <div>
        <?php echo $this->render('_searchUser', ['model' => $searchModel]); ?> 
    </div>            

    <div>
        <?= ListView::widget([
            'id'=>'member-list',
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' =>'_singleUser',
            'summary' =>'',
        ]) ?>
    </div>

</div>

<?php
//for banning Users
Dialog::begin([
    'id'=>'dlg-ban',
    'clientOptions' => [
        'modal' => true,
        'title'=>YBoard::t('yboard', 'Ban User'),
        'autoOpen'=>false,
        'modal'=>true,   
        'height'=>'auto',
        'width'=>'300',  
    ],
]);

        echo $this->render('../forum/_banForm', ['model'=>new YBoardMessage(['sendfrom'=>Yii::$app->user->id, 'sendto'=>0])]);

Dialog::end();

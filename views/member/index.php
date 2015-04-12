<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use app\modules\yboard\YBoard;

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
        <?php echo $this->render('_search', ['model' => $searchModel]); ?> 
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

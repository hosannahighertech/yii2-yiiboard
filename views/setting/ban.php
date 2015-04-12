<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\yboard\models\YBoardBanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = YBoard::t('yboard', 'Yboard Bans');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yboard-ban-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(YBoard::t('yboard', 'Create {modelClass}', [
    'modelClass' => 'Yboard Ban',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'ip',
            'email:email',
            'message',
            // 'expires',
            // 'banned_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

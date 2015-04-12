<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardMessage $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => YBoard::t('yboard', 'YBoard Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yboard-message-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(YBoard::t('yboard', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(YBoard::t('yboard', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => YBoard::t('yboard', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sendfrom',
            'sendto',
            'subject',
            'content:ntext',
            'create_time',
            'read_indicator',
            'type',
            'inbox',
            'outbox',
            'ip',
            'post_id',
        ],
    ]) ?>

</div>

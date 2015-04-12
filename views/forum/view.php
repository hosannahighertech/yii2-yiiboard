<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardForum $model
 */

$this->title = $model->name;  


$this->params['breadcrumbs']=[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	['label' => YBoard::t('yboard', 'YBoard Forums'), 'url' => ['index']],
    $this->title
];

?>
<div class="yboard-forum-view">

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
            'cat_id',
            'name',
            'subtitle',
            'type',
            'public',
            'locked',
            'moderated',
            'sort',
            'num_posts',
            'num_topics',
            'last_post_id',
            'poll',
            'membergroup_id',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardMessage $model
 */

$this->title = YBoard::t('yboard', 'Update {modelClass}: ', [
  'modelClass' => 'YBoard Message',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => YBoard::t('yboard', 'YBoard Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = YBoard::t('yboard', 'Update');
?>
<div class="yboard-message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

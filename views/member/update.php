<?php

use yii\helpers\Html;
use app\modules\yboard\YBoard;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardMember $model
 */

$this->title = YBoard::t('yboard', 'Update {modelClass}: ', [
  'modelClass' => 'YBoard Member',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => YBoard::t('yboard', 'YBoard Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = YBoard::t('yboard', 'Update');
?>
<div class="yboard-member-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

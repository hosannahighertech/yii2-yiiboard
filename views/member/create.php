<?php

use yii\helpers\Html;
use app\modules\yboard\YBoard;

/**
 * @var yii\web\View $this
 * @var app\modules\yboard\models\YBoardMember $model
 */

$this->title = YBoard::t('yboard', 'Add Forum Profile');
$this->params['breadcrumbs'][] = ['label' => YBoard::t('yboard', 'Member List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yboard-member-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

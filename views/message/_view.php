<?php
/* @var $this MessageController */
/* @var $model YBoardMessage */

use yii\helpers\Html;
use app\modules\yboard\components\DateTimeCalculation;
use Yii;
?>

<div>
    <?= Html::activeLabel($model, 'sendfrom') ?>:
    <span  class="header4 alert-danger"><?= $model->sender->profile->username ?></span> 
    (<?= DateTimeCalculation::short($model->create_time) ?>)
</div>

<div>
    <?= Html::activeLabel($model, 'subject') ?>:
    <span  class="header4"><?= Html::encode($model->subject) ?></span>
</div>
 

    
<div class="alert alert-warning panel" style="max-width:400px;">
    <?= $model->content ?>
</div>
 

<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use app\modules\yboard\models\YBoardForum;
use  app\modules\yboard\YBoard;
?>
<div class="container">
    <div class="row">
        <div class="col-md-1" style="padding:5px 0 0 5px;">
            <?php if(!\Yii::$app->user->isGuest) echo Html::a(YBoard::t('yboard','Mark all read'), ['forum/mark-all-read'], ['class'=>'btn btn-warning btn-xs',]); ?>
        </div>
        
        <div class="col-md-7 hidden-xs">
        </div>
        
        <div class="col-md-4 hidden-xs" style="padding:5px 0 5px 5px;">
            <?= Html::dropDownList('yboard-jumpto', '', 
                ArrayHelper::map(YBoardForum::getForumOptions(Yii::$app->user->isGuest, Yii::$app->user->id), 'id', 'name', 'group'), 
                [
                    'empty'=>YBoard::t('yboard','Select forum'),
                    'onchange'=>"
                        group = $(this.options[this.selectedIndex]).closest('optgroup').prop('label');
                        window.location.href='" . BaseUrl::toRoute(['forum']) . "?id='+$(this).val()",
                    'class'=>'form-control',
                    'id'=>'forum-categories-list',
            ]) ?>  

            <?php 
            if(isset($_GET['id']))
                $this->registerJs("$('#forum-categories-list option[value=".$_GET['id']."]').prop('selected', true);");
            ?> 
        </div>
    </div>
</div>

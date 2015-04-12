<?php
/* @var $this SettingController */
/* @var $data YBoardForum (category) */
/* @var $forum[] YBoardForum */

use yii\helpers\Html;
use yii\web\JsExpression;
use app\modules\yboard\YBoard;

$forumitems = array();
foreach($forum as $forumdata) {
	$forumitems['frm_'.$forumdata->id] = $this->render('_forum', array('forumdata'=>$forumdata), true);
}
?>
 
    <div class="row category"> 
        <div class="col-md-10">
            <span  class="header2"><?php echo Html::encode($data->name); ?></span>
            <p><?php echo Html::encode($data->subtitle); ?></p>
        </div>
        
        <div class="col-md-2 pad5-top">
            <?php echo Html::button(YBoard::t('yboard','Edit Category'), ['class'=>'btn btn-warning btn-xs','onclick'=>'editCategory(' . $data->id . ',"' . YBoard::t('yboard','Edit category') . '", "' . \Yii::$app->urlmanager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/get-forum') .'")']); ?>
        </div>   
    </div>

    <div class="row">  
        <div class="col-md-12">
            <?php 
                echo \yii\jui\Sortable::widget([
                    'id' => 'sortfrm' . $data->id,
                    'items' => $forumitems,
                    'options'=>['style'=>'list-style:none; margin-top:1px', 'class'=>'forum-item'],
                    'clientOptions'=>[
                        'delay'=>'100',
                        'update'=>new JsExpression('function(){Sort(this,"' . \Yii::$app->urlmanager->createAbsoluteUrl(Yii::$app->controller->module->id.'/setting/ajax-sort') . '");}'),
                    ],
                ]);
            ?> 
        </div> 
    </div> 

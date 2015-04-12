<?php
/* @var $post_id integer */
use app\modules\yboard\YBoard;
use app\modules\yboard\models\YBoardUpvoted; 
use app\modules\yboard\models\YBoardMember; 
use yii\helpers\Html;

$models = YBoardUpvoted::find()->where(['post_id'=>$post_id])->all();
$count = count($models);


if($count) {
	echo '<div class="post-upvote-footer">' . PHP_EOL;
	echo YBoard::t('yboard', 'This post is appreciated by {no} member{plural}', ['no'=>$count, 'plural'=>$count>1?'s':'']).' '; 
    $users = [];
	foreach($models as $model) {
		$member = YBoardMember::findOne($model->member_id);
		if($member !== null) {
			$users[] = Html::a(Html::encode($member->profile->username), ["member/view", "id"=>$member->id], ['target'=>'_blank']);
		}
	} 
    
	$members =  '<b>'.implode(', ', $users).'</b>'; 
    echo Html::a(YBoard::t('yboard', '[view]'), '#', ['onclick'=>'showAppreciation(\''.$members.'\'); return false;']);
    
	echo '</div>' . PHP_EOL;
}


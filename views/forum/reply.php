<?php
/* @var $this ForumController */
/* @var $forum YBoardForum */
/* @var $topic YBoardTopic */
/* @var $post YBoardPost */

use app\modules\yboard\YBoard;

//print_r($forum); die();

$this->params['breadcrumbs']=[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	['label'=>$forum->name, 'url'=>['forum/forum', 'id'=>$forum->id]],
	['label'=>$topic->title, 'url'=>['forum/topic', 'id'=>$topic->id]],    
	YBoard::t('yboard', 'Reply'),
];


$items = [
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	['label'=>YBoard::t('yboard', 'Members'), 'url'=>['member/index']]
];

$this->title = YBoard::t('yboard', 'New Reply');
?>
<div id="yboard-wrapper">
	<?php echo $this->render('_form', ['post'=>$post, 'hide_title'=>true]); ?>
</div>

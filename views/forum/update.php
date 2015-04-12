<?php
/* @var $this ForumController */
/* @var $forum YBoardForum */
/* @var $topic YBoardTopic */
/* @var $post YBoardPost */

use app\modules\yboard\YBoard;
 
$this->params['breadcrumbs'] = [
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	['label'=>$forum->name, 'url'=>['/forum/forum/forum', 'id'=>$forum->id]],
	['label'=>$topic->title, 'url'=>['/forum/forum/forum', 'id'=>$forum->id]],
	YBoard::t('yboard', 'Change'),
];


$items =[
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['/forum/forum/index']],
	['label'=>YBoard::t('yboard', 'Members'), 'url'=>['/forum/member/index']]
];
$this->title = YBoard::t('yboard', 'Updating {post}', ['post'=>$topic->title]);
?>
<div id="yboard-wrapper"> 
	
	<?php echo $this->render('_form', ['post'=>$post]); ?>
</div>

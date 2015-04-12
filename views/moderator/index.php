<?php
/* @var $this ForumController */
/* @var $model YBoardSetting */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
use app\modules\yboard\models\YBoardSetting;
use app\modules\yboard\YBoard;
 
$this->params['breadcrumbs'] = [
	['label'=>YBoard::t('yboard', 'Forums'), 'url'=>['forum/index']],
	YBoard::t('yboard', 'Settings'),
];


$this->title = YBoard::t('yboard', 'Moderator CP');

$item = [
	['label'=>YBoard::t('yboard', 'Manage Forum'), 'url'=>['setting/forum']],
	['label'=>YBoard::t('yboard', 'Member groups'), 'url'=>['setting/group']],
	['label'=>YBoard::t('yboard', 'Moderators'), 'url'=>['setting/moderator']],
	['label'=>YBoard::t('yboard', 'Webspiders'), 'url'=>['setting/spider']],
];

$this->params['adminMenu'] ['title'] = YBoard::t('yboard', 'Moderator');

$this->params['adminMenu'] = [
	['label'=>YBoard::t('yboard', 'Manage Bans'), 'url'=>['moderator/bans']],
	['label'=>YBoard::t('yboard', 'Pending Posts '), 'url'=>['moderator/approve']],
	['label'=>YBoard::t('yboard', 'Reported Posts'), 'url'=>['moderator/reported']],
];
?>

<div id="yboard-wrapper">
    <div class="row">  
        <div class="col-md-12"> 
            <ul class="list-group">
                <li class="header2 header2-style"><?= YBoard::t('yboard', 'Pending for Moderation') ?></li>
                <li class="list-group-item header4"><?= YBoard::t('yboard', '{number} Pending Topics', ['number'=>$pendingTopics>0?$pendingTopics:YBoard::t('yboard', 'No')]) ?></li> 
                <li class="list-group-item header4"><?= YBoard::t('yboard', '{number} Pending Posts', ['number'=>$pendingPosts>0?$pendingPosts:YBoard::t('yboard', 'No')]) ?></li> 
            </ul>
        </div>
	</div> 
    
    <div class="row">  
        <div class="col-md-12"> 
            <ul class="list-group">
                <li class="header2 header2-style"><?= YBoard::t('yboard', 'Bans Ending Soon') ?></li>
                <li class="list-group-item header4">
                    <?= YBoard::t('yboard', '{number} Banned Members', ['number'=>$bannedUsers>0?$bannedUsers:YBoard::t('yboard', 'No')]) ?>
                </li> 
                <li class="list-group-item header4">
                    <?= YBoard::t('yboard', '{number} Banned Emails', ['number'=>$bannedEmails>0?$bannedEmails:YBoard::t('yboard', 'No')]) ?>
                </li> 
                <li class="list-group-item header4">
                    <?= YBoard::t('yboard', '{number} Banned IPs', ['number'=>$bannedIps>0?$bannedIps:YBoard::t('yboard', 'No')]) ?>
                </li> 
            </ul>
        </div>
	</div> 
</div> 


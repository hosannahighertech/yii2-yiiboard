<?php
/* @var $this ForumController */
/* @var $error Exception */

use yii\helpers\Html;
use app\modules\yboard\YBoard;


$this->title = YBoard::t('yboard', 'We found problem!');
 
$this->params['breadcrumbs'] = [
	['label'=>YBoard::t('yboard', 'Forum'), 'url'=>['forum/index']],
    YBoard::t('yboard', 'Error reporter')
];  
 
?>

<div id="yboard-wrapper" class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="header2 pad5"><?php echo YBoard::t('yboard', 'We\'ve got Error with Code ') . ' ' . $exception->statusCode; ?></p>
        </div>

        <div class="error col-md-12">
            <div class=" alert alert-warning panel " style="display:inline-block;">
                <p><?= YBoard::t('yboard', ' Dear customer, we are sorry that you got this message. Please try to check URL to see if you have typed Correctly') ?></p>
                <p><?= YBoard::t('yboard', 'If the error persists Please send us full error below with error code and URL. Your subject should be RE: Site Error.') ?></p>
                <p><?= YBoard::t('yboard', 'Thank you!') ?></p>
            </div><br>
             
             <div class="alert alert-danger" style="display:inline-block;">
                <span class="header3"><?= YBoard::t('yboard', 'Message') ?></span><br>
                <?php echo Html::encode($exception->getMessage()); ?>
             </div>

        </div>
    </div>
	
</div>

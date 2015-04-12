<?php
use app\modules\yboard\YBoard;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;
use app\modules\yboard\models\YBoardMessage; 

/* @var $this \yii\web\View */
/* @var $content string */ 
 

$this->registerJs('hljs.initHighlightingOnLoad();');

$unread = YBoardMessage::find()->inboxScope()->unreadScope()->andWhere(['sendto' =>\Yii::$app->user->id])->count(); 
$total = YBoardMessage::find()->inboxScope()->andWhere(['sendto' =>\Yii::$app->user->id])->count(); 


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?> 
    <?php
            NavBar::begin([
                'brandLabel' => '<p>'.Html::img($this->context->module->getRegisteredImage('logo.png'), ['class'=>'hidden-xs' ])
                        .Html::img($this->context->module->getRegisteredImage('logo_22.png'), ['class'=>'visible-xs' ])                    
                        .' '.$this->context->module->forumTitle.'</p>',
                'brandUrl' => ['/'.$this->context->module->id.'/forum/'],
                'options' => [
                    'class' => 'navbar-custom navbar-static-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right visible-xs'],
                'items' =>  isset($this->context->module->params['foroMenu'])?
                    array_merge($this->context->module->params['foroMenu'],
                         [
                            ['label' => YBoard::t('yboard', 'Login'), 'url' => ['/profile/account/login'], 'visible' => Yii::$app->user->isGuest],
                            ['label' => YBoard::t('yboard', 'Logout'), 'url' => ['/profile/account/logout'], 'visible' => !Yii::$app->user->isGuest, 'linkOptions' => ['data-method' => 'post']]
                         ]
                    )
                    :[]
            ]);
            NavBar::end();
        ?>
     

    <!-- Begin page content -->
    <div class="container-fluid" id="submenu">
        <div class="row">
            <div class="col-md-6">    
                <?php if(!Yii::$app->user->isGuest) : ?>
                   <div class="menu-link  hidden-xs">
                        <?=Html::a( YBoard::t('yboard', 'Welcome back {uname}!', ['uname'=>Yii::$app->user->identity->username]), ['member/view','id'=>Yii::$app->user->id]) ?> 
                        <div class="pull-right ">
                            <?= Html::a(Html::img($this->context->module->getRegisteredImage('msg.png')).' '.YBoard::t('yboard', '({unread} of {total})',['unread'=>$unread, 'total'=>$total]), ['message/inbox'], ['title'=>YBoard::t('yboard','Private Messages')]) ?>
                        </div>
                    </div>  
                <?php endif; ?> &nbsp; 
            </div>
                    
            <div class="col-md-3"> 
                <div class="menu-link">
                    <?php if(!Yii::$app->user->isGuest) : ?>
                    <?=Html::a(Html::img($this->context->module->getRegisteredImage('usercp.png')).' '.YBoard::t('yboard', 'User CP'), ['member/profile','id'=>Yii::$app->user->id], ['title'=>YBoard::t('yboard', 'User Control Panel'),'style'=>'vertical-align:bottom; ']) ?>
                    <span class="visible-xs pull-right">
                            <?= Html::a(Html::img($this->context->module->getRegisteredImage('msg.png')).' '.YBoard::t('yboard', '({unread} of {total})',['unread'=>$unread, 'total'=>$total]), ['message/inbox'], ['title'=>YBoard::t('yboard','Private Messages')]) ?>
                    </span>
                    <?php endif; ?> &nbsp;
                    
                    <?php if(Yii::$app->user->can('moderator')) : ?>
                    <?=Html::a(Html::img($this->context->module->getRegisteredImage('modcp.png')).' '.YBoard::t('yboard', 'Mod CP'), ['moderator/index'], ['title'=>YBoard::t('yboard', 'Moderator Control Panel'),'style'=>'vertical-align:bottom;']) ?>
                    <?php endif; ?> &nbsp; 
                    
                    <?php if(Yii::$app->user->can('admin')): ?>
                    <?= Html::a(Html::img($this->context->module->getRegisteredImage('admincp.png')).' '.YBoard::t('yboard', 'Admin CP'), ['setting/index'], ['title'=>YBoard::t('yboard', 'Admin Control Parent'),'style'=>'vertical-align:bottom;']) ?>            
                    <?php endif; ?>
                </div>
            </div>
                  
            <div class="col-md-3 hidden-xs">   
               <?php 
                    if(isset($this->context->module->params['foroMenu']))
                    {
                        echo '<div  class="yboard-menu pull-right">';
                            echo Menu::widget([
                                'items' =>array_merge($this->context->module->params['foroMenu'],
                                     [
                                        ['label' => YBoard::t('yboard', 'Login'), 'url' => ['/profile/account/login'], 'visible' => Yii::$app->user->isGuest],
                                        ['label' => YBoard::t('yboard', 'Logout'), 'url' => ['/profile/account/logout'], 'visible' => !Yii::$app->user->isGuest,'template' => '<a data-method="post" href="{url}">{label}</a>']
                                     ]
                                )
                                    
                            ]);
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </div>
    
    <?php if(isset($this->params['breadcrumbs'])):  ?>
        <div id="breadcrumbs">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </div>
    <?php endif; ?>
    
    <div class="container-fluid">
        <?= $content ?>
    </div>

    <footer class="footer">
        <div class="container"  id="footer" >
            <p class="pad5">YiiBoard <?= YBoard::t('yboard','v{v}', ['v' =>$this->context->module->version]) ?> &copy; 2013-2014,  Hosanna Higher Technologies Ltd.  <?= Yii::powered() ?></p>
        </div>
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

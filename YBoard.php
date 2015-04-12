<?php

namespace app\modules\yboard;  
 
use app\components\ckeditor\CKEditorAsset;
use Yii;
use app\modules\yboard\YBoard;
use app\modules\yboard\models\YBoardSpider;
use app\modules\yboard\models\YBoardSession;
use app\modules\yboard\models\YBoardMember;
use app\modules\yboard\models\YBoardPost;
use app\modules\yboard\models\YBoardTopic;
use app\modules\yboard\models\YBoardMessage;
use app\modules\yboard\models\YBoardBan;

use yii\base\Event;
use yii\web\ErrorHandler;

class YBoard extends \yii\base\Module 
{
    private $bundleInstance = null; //assets bundle
    
    public $controllerNamespace = 'app\modules\yboard\controllers';
    
    public $defaultRoute = 'forum';  
    public $css = ['forum.css'];  
    public $version = '0.2.0';
	public $forumTitle = 'Hosanna Forums - Home of Programmers';
	
    public $userClass = 'User'; 
    public $profile = ['edit'=>'', 'view'=>''];
    //Columns essential
	public $userIdColumn = 'id';
	public $userNameColumn = 'username'; 
    
    //additional but important
	public $birthdateColumn = null; 
	public $genderColumn = null; 
	public $regdateColumn = null; 
    
	public $userMailColumn = false;
	public $topicsPerPage = 10;
	public $postsPerPage = 5; 
	public $onlineLimit = 20;//900000; //milliseconds [15min]
    
	public $view = null;
    
    public $layout='forum';  

    public function init() 
    {
        parent::init();  
        Yii::setAlias('yboard', dirname(dirname(__DIR__)).'/modules/yboard/');
		
        // initialize the module with the configuration loaded from config.php
        Yii::configure($this, require(__DIR__ . '/config.php'));
        
        //configure widgets
        Yii::$container->set('yii\widgets\LinkPager', [
            'options'=>[
                'class'=>'pagination ',
            ],
            'activePageCssClass'=>'pager-active ',
            'disabledPageCssClass'=>'pager-disabled ',
            'nextPageCssClass'=>'pager-next',
            'prevPageCssClass'=>'pager-prev',
        ]);
        
        
        //override default error handler
        $handler = new ErrorHandler(['errorAction' => $this->id.'/forum/error']);
        Yii::$app->set('errorHandler', $handler);
        $handler->register(); 
        //reload assets
        Yii::$app->assetManager->forceCopy = true;            
        $this->registerAssets(); 
        //register translation cat
        $this->registerTranslations();
		
    }
    
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {    
            
            //update users online information            
            $this->updateOnlineStatus($action);  
        
            // register visit by webspider
            if(isset($_SERVER['HTTP_USER_AGENT'])) {
                $spider = YBoardSpider::find()
                    ->where(['user_agent'=>$_SERVER['HTTP_USER_AGENT']])
                    ->one();
            } else {
                $spider = null;
            }
            
            if($spider != null) {
                $spider->setScenario('visit');
                $spider->hits++;
                $spider->last_visit = null;
                $spider->save();
            }  
            
            //menu fixed for Views
            $approvals1 = YBoardPost::find()
                ->unapprovedScope()
                ->count();
            $approvals2 = YBoardTopic::find()
                ->andWhere(['approved' => 0])
                ->count();
            
            $reports = YBoardMessage::find()
                ->reportScope()
                ->unreadScope()
                ->count();
                
            $this->params['foroMenu'] = [
                ['label'=>Yii::t('app', 'Members'), 'url'=>['member/index']],
                ['label'=>Yii::t('app', 'Pending'). ' (' . ($approvals1+$approvals2) . ')', 'url'=>['moderator/approve'], 'visible'=>Yii::$app->user->can('moderator')],
                ['label'=>Yii::t('app', 'Reported'). ' (' . $reports . ')', 'url'=>['moderator/reported'], 'visible'=>Yii::$app->user->can('moderator')],
            ];
                        
			return true;
		}
		else
			return false;
    }
    
    
    public function registerAssets() 
    {
        $view = Yii::$app->view;          
        //Yii::$app->assetManager->forceCopy = true;
        $this->bundleInstance = \app\modules\yboard\YBoardAsset::register($view);
        $this->bundleInstance->css = $this->css;
        $this->bundleInstance->publish(Yii::$app->assetManager); 
 	}
    
    public function updateOnlineStatus($action) 
    {	        
        //Timed JS function
        //fetch current user list and add update statistics
        //js to Update it for a time
        session_start(); 
        Yii::$app->view->registerJs(" 
            function updateOnlineUsers() {
                $.get('".Yii::$app->urlManager->createUrl([$this->id.'/member/update-online-status', 'id'=>Yii::$app->session->id, 'uid'=>Yii::$app->user->id])."')
                .done(function(data){
                    data = $.parseJSON(data);
                    console.log(data);
                    $('#online-record').html(data.message);
                }); 
            }
            
            //call function to immediately update online users
            updateOnlineUsers();
            setInterval(updateOnlineUsers,".($this->onlineLimit*1000).");
        "); 
            
        // register visit by guest or user
        /*$session = YBoardSession::findOne(Yii::$app->session->id);
        if($session == null) {
            $session = new YBoardSession;
            $session->setAttributes([
                'id'=>Yii::$app->session->id,
                'user_id'=>Yii::$app->user->isGuest ? NULL : Yii::$app->user->id,
                'last_visit'=>time(),
            ]); 
        }               
        $session->save() ;  */ 
        // register last visit by member
        if(!Yii::$app->user->isGuest) 
        {            
            $model = YBoardMember::findOne(Yii::$app->user->id);
            if($model !== null) {
                $model->last_visit 	= date('Y-m-d H:i:s');
                $model->save();        
                
                //banned user are not allowed to access anything. So they have to be guests                 
                if($action->id!='banned' &&  $action->id!='error')
                {  
                    Event::on(self::className(), self::EVENT_AFTER_ACTION, function ($event) {
                                //if banned redirect to banned
                        
                        $ban = YBoardBan::find()
                            ->where(['user_id'=>Yii::$app->user->id])
                            ->orWhere(['ip'=>Yii::$app->request->userIP]) 
                            ->andWhere('expires>'.time())
                            ->one();
                        if($ban!=null)
                        {  
                            return Yii::$app->response->redirect([$this->id.'/forum/banned', 'id' => $ban->id])->send();
                        }
                    }); 
                }                     
                
            } else {                 
                //redirect to associate member with forum account
                //no user can access forum logged in without member account
                if($action->id!='banned' &&  $action->id!='error' && $action->id!='associate' && $action->controller->id!='member')
                {
                    Event::on(self::className(), self::EVENT_AFTER_ACTION, function ($event) 
                    {
                        return Yii::$app->response->redirect([$this->id.'/member/associate', 'id' => Yii::$app->user->id])->send();
                    }); 
                }                
            }
        }  
    }
    
    public function getGroupImages()
    { 
        $data = [];
        //get all image files with a .jpg extension. 
        $images = glob($this->bundleInstance->basePath.'/images/groups/' . "*.png"); 
        foreach($images as $image)
        {
          $data[basename($image)] =  basename($image);
        } 
        return $data;
    }
    
    public function getRegisteredImage($filename) {
        return $this->bundleInstance->baseUrl .'/images/'. $filename;
    }
    
    public function getSocialImage($filename) {
        return $this->bundleInstance->baseUrl .'/images/social/'. $filename;
    }
    
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['@yboard/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@yboard/messages',
            'fileMap' => [
                '@yboard/yboard' => 'validation.php',  
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('@yboard/' . $category, $message, $params, $language);
    }
} 

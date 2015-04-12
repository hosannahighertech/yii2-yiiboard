<?php

namespace app\modules\yboard\controllers;

use app\modules\yboard\YBoard;
use yii\helpers\Html;

use Yii;
use hosanna\profile\models\User;
use app\modules\yboard\models\YBoardMessage;
use app\modules\yboard\models\YBoardMessageSearch;
use app\modules\yboard\models\YBoardPost; 
use app\modules\yboard\models\YBoardMember;
 
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * MessageController implements the CRUD actions for YBoardMessage model.
 */
class MessageController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all YBoardMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->can('app.forum.message.index'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $searchModel = new YBoardMessageSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single YBoardMessage model.
     * @param string $id
     * @return mixed
     *
     * handle Ajax call for viewing message
	 */
	public function actionView() {
        if(!Yii::$app->user->can('app.forum.message.view'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];
		if(isset($_POST['id'])) {
			$model = YBoardMessage::findOne($_POST['id']);
			if($model !== null /*&& (Yii::$app->user->can('moderator')|| $model->sendto == Yii::$app->user->id || $model->sendfrom == Yii::$app->user->id)*/) {
				$json['success'] = 'yes';
				$json['html'] = $this->renderPartial('_view', ['model' => $model]);
                $model->read_indicator = 1;
                $model->update(); 
			} else { 
				$json['success'] = 'no';
				$json['message'] = YBoard::t('yboard', 'Message not found.');
			}
		} else {
			$json['success'] = 'no';
			$json['message'] = YBoard::t('yboard', 'Message not found.');
		} 
		echo json_encode($json);
		Yii::$app->end();
	}
    
    //Handle Ajax replies
    public function actionReply()
    {
        if(!Yii::$app->user->can('app.forum.message.reply'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $json = []; 
        
        $model = new YBoardMessage;
        $model->load(Yii::$app->request->post());
        $model->setAttributes([
            'ip'=>Yii::$app->request->getUserIP(), 
            'create_time'=>new \yii\db\Expression('NOW()')
        ]);
        
        if($model->save()) 
        {
            $json['success'] = 'yes';
            $json['message'] = YBoard::t('yboard', 'PM Sent Successfully');
        } else 
        {
            $json['success'] = 'no';
            $json['message'] = YBoard::t('yboard', 'Could not send your private message. Try again').json_encode($_POST)." ===== ".json_encode($model->errors);
        }
        
        echo json_encode($json);
        Yii::$app->end();
        
    }

    /**
     * Creates a new YBoardMessage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->can('app.forum.message.create'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $model = new YBoardMessage;
        $model->load(Yii::$app->request->post());
        $model->setAttributes([
            'ip'=>Yii::$app->request->getUserIP(), 
            'create_time'=>new \yii\db\Expression('NOW()')
        ]);
        
        $json = [];
        //are we sending single or multiple messages?
        if($model->usernames!=null)
        {
            $usernames = explode(',', $model->usernames);
            $json['success'] = 'no';
            $str = '';
            foreach($usernames as $usr)
            {
                $userModel = User::find()->where(['username'=>$usr])->one();
                if($userModel!=null)
                {
                    $model->setAttribute('sendto', $userModel->id);
                    $model->id=null;
                    $model->isNewRecord = true;
                    
                    if($model->save())
                    {
                        $str.=YBoard::t('yboard', 'Message sent to {usr}', ['usr'=>$usr]);
                        $str.= "\n";
                    } 
                    else   
                    {
                        $str.=YBoard::t('yboard', 'Message not sent to {usr}', ['usr'=>$usr]);
                        $str.= "\n";
                    }
                                    
                }
            } 
            $json['message'] = $str;
        }
        else
        {
            if($model->save()) {
                $json['success'] = 'yes';
                $json['message'] = YBoard::t('yboard', 'PM Sent Successfully');
            } else {
                $json['success'] = 'no';
                $json['message'] = YBoard::t('yboard', 'Could not send your private message. Try again').json_encode($_POST)." ===== ".json_encode($model->errors);
            }
        }
        echo json_encode($json);
        Yii::$app->end();
    }
 

    /**
     * Deletes an existing YBoardMessage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if(!Yii::$app->user->can('app.forum.message.delete', ['message'=> $model]))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        if(!\Yii::$app->request->isAjax)
            Yii::$app->end();
            
        if($model->delete())
            echo json_encode(['success'=>true, 'msg'=>YBoard::t('yboard', 'Message Successfully Deleted')]);
        else
            echo json_encode(['success'=>false, 'msg'=>YBoard::t('yboard', 'Message Deletion Failed')]);
    
        Yii::$app->end();
    }
    
    /**
	 * handle Ajax call for sending a report on a post
	 */
	public function actionSendReport() {
        if(!Yii::$app->user->can('app.forum.message.send-report'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];
		if(isset($_POST['YBoardMessage'])) {
			$model = new YBoardMessage;
			$model->attributes = $_POST['YBoardMessage'];
            
            $post = YBoardPost::findOne($model->post_id);
            
			$model->subject = YBoard::t('yboard', 'Post reported:').' '.$post->subject;
			$model->sendto = 0; //reported post No recipient
			$model->sendfrom = Yii::$app->user->id;
			$model->outbox = 0;// not put to outbox
			$model->type = 2; //notifications
			$model->ip = Yii::$app->request->userIP;
            
            $user = YBoardMember::findOne(Yii::$app->user->id);
            
            $content = YBoard::t('yboard', 'Reporter: {user} 
            Reason:{reason} . The link to the post is attached below"', [
                'reason'=>$model->content,
                'user'=>$user->profile->username,
            ])
            .'<p>'
            .Html::a( YBoard::t('yboard', 'click to view post'),['/'.$this->module->id.'/forum/topic',  'id' => $post->topic_id, '#' => $post->id], ['target'=>'_blank'])
            .'</p> <p>'
            .Html::a( YBoard::t('yboard', 'click to view reporter [{username}]', ['username'=>$user->profile->username]), ['/'.$this->module->id.'/member/view', 'id'=>$user->id], ['target'=>'_blank']).'</p>'
             .'<p>'
            .YBoard::t('yboard', ' Reporter IP: {ip}]', ['ip'=>Yii::$app->request->userIP])
            .'</p>';
            
            $model->content = $content;
			
            if($model->save()) {
				$json['success'] = 'yes';
				$json['message'] = YBoard::t('yboard', 'Thank you for your report.');
			} else {
				$json['success'] = 'no';
				$json['message'] = YBoard::t('yboard', 'Could not register your report.').json_encode($_POST)." ===== ".json_encode($model->errors);
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
    
    //PM Inbox
    public function actionInbox($id=null) {
        
        if(!Yii::$app->user->can('app.forum.message.inbox'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        if($id==null) 
			$id = Yii::$app->user->id; 
            
        $query = null;
        $count = [];
         
        
        $query = YBoardMessage::find()
            ->inboxScope()
            ->andWhere(['sendto'=>Yii::$app->user->id]);
          
        $count['inbox'] = $query->count();
        $count['outbox'] = YBoardMessage::find()
            ->outboxScope()
            ->andWhere(['sendfrom'=>Yii::$app->user->id])
            ->count(); 

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $this->render('inbox', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'count'=>$count
        ]);		
	}
    
    
	
	public function actionOutbox($id = null) {
        if(!Yii::$app->user->can('app.forum.message.outbox'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		if($id==null) 
			$id = Yii::$app->user->id; 
            
        $count['inbox'] = YBoardMessage::find()
            ->inboxScope()
            ->andWhere(['sendto'=>Yii::$app->user->id])
            ->count(); 
        $query = YBoardMessage::find()
                ->outboxScope()
                ->andWhere(['sendfrom'=>Yii::$app->user->id]);
        
        $count['outbox'] = $query->count();
                
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        //print_r(Yii::$app->request->queryParams); die();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams); 
        
        return $this->render('outbox', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'count'=>$count
        ]); 
	}

    /**
     * Finds the YBoardMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return YBoardMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = YBoardMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

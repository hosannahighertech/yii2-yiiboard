<?php

namespace app\modules\yboard\controllers;

use Yii;
use app\modules\yboard\YBoard;
use app\modules\yboard\models\YBoardSetting;
use app\modules\yboard\models\YBoardBan;
use app\modules\yboard\models\YBoardForum;
use app\modules\yboard\models\YBoardMember;
use app\modules\yboard\models\YBoardMessage;
use app\modules\yboard\models\YBoardMembergroup;
use app\modules\yboard\models\YBoardPost;
use app\modules\yboard\models\YBoardTopic;
use app\modules\yboard\models\YBoardForumSearch;
use app\modules\yboard\models\YBoardVote;
use app\modules\yboard\models\YBoardPoll;
use app\modules\yboard\models\YBoardChoice;
use app\modules\yboard\models\YBoardLogTopic; 
use app\modules\yboard\models\YBoardUpvoted; 
use app\modules\yboard\components\DateTimeCalculation;

use app\modules\yboard\components;

use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/**
 * ForumController implements the CRUD actions for YBoardForum model.
 */
class ForumController extends \yii\web\Controller
{
	
    public $poll;
	public $choiceProvider;
	public $voted;
    
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
     * Lists all YBoardForum models.
     * @return mixed
     */
    public function actionIndex()
    {  
        $NumberOfTopics = YBoardSetting::find()->where(['key'=>'latest_topic'])->one();
        $NumberOfReplies = YBoardSetting::find()->where(['key'=>'latest_reply'])->one();
        
        $query = YBoardForum::find()
            ->sortedScope()
            ->andWhere('cat_id IS NULL') ;     
        		 
        if(Yii::$app->user->isGuest) {
            $query->categoriesScope()
                ->publicScope()
                ->memberGroupScope()  ;      
        } 
        else if(Yii::$app->user->can('admin')) 
        {
            $query->categoriesScope() ;
        } else 
        {  
            $query->memberGroupScope(Yii::$app->user->id) ;
        }  
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);  
        
        //get forum IDs allowed
        $fids = [];
        foreach($dataProvider->models as $cat)
        {
            foreach($cat->forums as $fmodel)
                $fids[] = $fmodel->id;
        } 

        $recentTopics = YBoardTopic::find()
            ->where(['forum_id'=>$fids])
            ->orderBy('id DESC')
            ->limit($NumberOfTopics==null ? 10 : $NumberOfTopics->value)
            ->all();
         
        $recentReplies = YBoardPost::find()
            ->where(['forum_id'=>$fids])
            ->andWhere('original_post=0')
            ->orderBy('create_time DESC')
            ->limit($NumberOfReplies==null ? 10 : $NumberOfReplies->value)
            ->all();
         

        return $this->render('index', [
            'dataProvider' => $dataProvider, 
            'recentTopics' => $recentTopics, 
            'recentReplies' => $recentReplies, 
        ]);
    }

/**
	 * Show forum with topics
	 */
	public function actionForum($id) {
        $forum = $this->findModel($id);
    
		if(Yii::$app->user->isGuest && $forum->public == 0) {
			throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no permission to view requested forum.'));
		}
        
		if($forum->membergroup_id != 0) {
			if(Yii::$app->user->isGuest) {
				throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no permission to view requested forum.'));
			} elseif(!Yii::$app->user->can('moderator')) {
				$groupId = YBoardMember::findOne(Yii::$app->user->id)->group_id;
				if($forum->membergroup_id != $groupId) {
					throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no permission to view requested forum.'));
				}
			}
		}
        
		if(isset($_GET['YBoardTopic_page']) && isset($_GET['ajax'])) {
            $topicPage = (int) $_GET['YBoardTopic_page'] - 1;
            Yii::$app->session->set('YBoardTopic_page', $topicPage);
			Yii::$app->session->set('YBoardForum_id', $id);
            unset($_GET['YBoardTopic_page']);
        } elseif(isset($_GET['ajax'])) {
            Yii::$app->session->set('YBoardTopic_page', 0);
		} elseif(Yii::$app->session->get('YBoardForum_id', null)!=null && Yii::$app->user->YBoardForum_id != $id) {
			unset(Yii::$app->user->YBoardForum_id);
			Yii::$app->session->set('YBoardTopic_page', 0);
		}
        
        $query = YBoardTopic::find()
                ->with('starter');
                
                if(!Yii::$app->user->can('moderator'))
                    $query->where(['approved'=>1]);
                    
                $query->andWhere(['forum_id'=>$forum->id])
                ->orWhere(['global'=>1])
                ->orderBy('global DESC, sticky DESC, last_post_id DESC');
                
		$dataProvider = new ActiveDataProvider([
			'query'=>$query,
			'pagination'=>[
				'pageSize'=>$this->module->topicsPerPage,
				'page' => Yii::$app->session->get('YBoardTopic_page', 0),
			],
		]);
        
		return $this->render('forum', [
            'forum' => $forum,
			'dataProvider' => $dataProvider
		]);
	} 
     
    public function actionTopic($id, $nav = null, $postId = null) {
		$topic = YBoardTopic::findOne($id);
		if($topic === null) {
			throw new NotFoundHttpException(YBoard::t('yboard', 'The requested topic does not exist.'));
		}
        
        $replyModel = new YBoardPost;
        $replyModel->topic_id = $topic->id; 
        $replyModel->subject = YBoard::t('yboard', 're:').$topic->title;
        
		$forum = YBoardForum::findOne($topic->forum_id);
		if(Yii::$app->user->isGuest && $forum->public == 0) {
			throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no permission to read requested topic.'));
		}
        
		if($topic->approved == 0 && !Yii::$app->user->can('moderator')) {
			throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no permission to read requested topic.'));
		}
        
		if($forum->membergroup_id != 0) {
			if(Yii::$app->user->isGuest) {
				throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no permission to read requested topic.'));
			} elseif(!Yii::$app->user->can('moderator')) {
				$groupId = YBoardMember::findOne(Yii::$app->user->id)->group_id;
				if($forum->membergroup_id != $groupId) {
					throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no permission to read requested topic.'));
				}
			}
		}
		$dataProvider = new ActiveDataProvider([
            'query'=>YBoardPost::find()
                    ->where(['approved'=>1])
                    ->andWhere(['topic_id'=>$topic->id])
                    ->orderBy('id')
                    ->with('poster'), 
			'pagination'=>array(
				'pageSize'=>$this->module->postsPerPage,
			),
		]);
		// Determine poll
 		$this->poll = YBoardPoll::find()->where(['post_id'=>$topic->first_post_id])->one();
		if($this->poll !== null) {
			$this->choiceProvider = new ActiveDataProvider([
                'query'=>YBoardChoice::find()
                        ->where(['poll_id'=>$this->poll->id])
                        ->orderBy('sort') ,
				'pagination'=>false,
			]);
			// Determine whether user has voted
			if(Yii::$app->user->isGuest) {
				$this->voted = true; // A guest may not vote and sees the result immediately
			} else {
 				$this->voted = YBoardVote::find()
                        ->where(['poll_id'=>$this->poll->id])
                        ->andWhere(['user_id'=> Yii::$app->user->id])
                        ->exists();
			}
			// Determine wheter the poll has expired
			if(!$this->voted && isset($this->poll->expire_date) && $this->poll->expire_date < date('Y-m-d')) {
				$this->voted = true;
			}
		}
		// Navigate to a post in a topic
		if(isset($nav)) {
			$cPage = $dataProvider->getPagination();
			if(is_numeric($nav)) {
                $count = YBoardPost::find()
                    ->where('topic_id = ' . $topic->id . ' and id <= ' . $nav . ' and approved = 1')
                    ->count();
				$page = ceil($count/$cPage->pageSize);
				$post = $nav;
			} else {
				$page = ceil($dataProvider->totalCount/$cPage->pageSize);
				$post = $topic->last_post_id;
			}
			if(Yii::$app->session->hasFlash('moderation')) {
				Yii::$app->session->setFlash('moderation', Yii::$app->session->getFlash('moderation'));
			}
            //set current page to calculated if we are having last page
            if(!isset($_GET['per-page']))
                $dataProvider->pagination->page = $page-1; //pages are 0 indexed
		}
		// Increase topic views
		$topic->updateCounters(['num_views'=>1]);
		// Register the last visit of a topic
		if(!Yii::$app->user->isGuest) {
			$topicLog = YBoardLogTopic::findOne(['member_id'=>Yii::$app->user->id, 'topic_id'=>$topic->id]);
			if($topicLog === null) {
				$topicLog = new YBoardLogTopic;
				$topicLog->member_id = Yii::$app->user->id;
				$topicLog->topic_id = $topic->id;
				$topicLog->forum_id = $topic->forum_id;
			}
			$topicLog->last_post_id = $topic->last_post_id;
			$topicLog->save();
		} 
        
        return $this->render('topic', array(
			'forum' => $forum,
			'reply' => $replyModel,
			'topic' => $topic,
			'dataProvider' => $dataProvider,
			'choiceProvider' => $this->choiceProvider,
			'postId' => $postId,
		));
	}

    /**
     * Creates a new YBoardForum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateTopic()
    {
        if(!Yii::$app->user->can('app.forum.forum.create-topic'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
         
        $post = new YBoardPost;
        //top post aka OP
        $post->original_post = 1;
        
		$poll = new YBoardPoll;
        
		if(isset($_POST['YBoardForum'])) {
			$post->forum_id = $_POST['YBoardForum']['id'];
			$forum = YBoardForum::findOne($post->forum_id);
		}
		if(isset($_POST['choice'])) {
			$choiceArr = $_POST['choice'];
			while(count($choiceArr) < 3) {
				$choiceArr[] = '';
			}
		} else {
			$choiceArr = array('', '', '');
		}
        //print_r(Yii::$app->request->post());die();
		if($post->load(Yii::$app->request->post())) {
			//$post->attributes = $_POST['YBoardPost'];
			$forum = YBoardForum::findOne($post->forum_id);
            $post->setAttributes([
                'approved'=>$forum->moderated? 0 : 1, 
                'user_id'=>Yii::$app->user->identity->id, 
            ]);
            
			if($post->save()) {
				// Topic
				$topic = new YBoardTopic;
				$topic->forum_id 		= $forum->id;
				$topic->title 			= $post->subject;
				$topic->first_post_id 	= $post->id;
				$topic->last_post_id 	= $post->id;
				$topic->approved 		= $post->approved;
				
                if(isset($_POST['sticky'])) { $topic->sticky = 1; }
				if(isset($_POST['global'])) { $topic->global = 1; }
				if(isset($_POST['locked'])) { $topic->locked = 1; }
				
                // Poll
				if(isset($_POST['YBoardPoll']) && isset($_POST['addPoll']) && $_POST['addPoll'] == 'yes') {
					$poll->attributes = $_POST['YBoardPoll'];
					$poll->post_id = $post->id;
					$poll->user_id = Yii::$app->user->identity->id;
					if(empty($poll->expire_date)) {
						unset($poll->expire_date);
					}
					$count = 0;
					$choices = $_POST['choice'];
					foreach($choices as $choice) {
						if(!empty($choice)) { $count++; }
					}
					if($poll->validate() && $count > 1) {
						$correct = true;
					} else {
						$correct = false;
						if($correct < 2) {
							$poll->addError('question', YBoard::t('yboard','A poll should have at least 2 choices.'));
						}
					}
				} else {
					$correct = true;
				}
				
				if($correct && $topic->save()) {
					$post->topic_id = $topic->id;
					$post->save();
                    
					if(!$forum->moderated) {
						$forum->updateCounters(['num_posts'=>1,'num_topics'=>1]);	
						$forum->last_post_id = $post->id;
						$forum->save(); 
					} else {
						Yii::$app->session->setFlash('moderation',YBoard::t('yboard', 'Your post has been saved. It has been placed in a queue and is now waiting for approval by a moderator before it will appear on the forum. Thank you for your contribution to the forum.'));
					}
					if(isset($_POST['YBoardPoll'])) {
						$poll->save();
						$choices = $_POST['choice'];
						$i = 1;
						foreach($choices as $choice) {
							if(!empty($choice)) {
								$ch = new YBoardChoice;
								$ch->choice = $choice;
								$ch->poll_id = $poll->id;
								$ch->sort = $i++;
								$ch->save();
							}
						}
					}
					return $this->redirect(['topic', 'id'=>$topic->id]);
				} else { 
					$post->delete();
				}
			}
		}
		
        return $this->render('create', [
			'forum' => $forum,
			'post' => $post,
			'poll' => $poll,
			'choices' => $choiceArr,
		]);
    }

    /**
     * Updates an existing YBoardForum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
		$post = YBoardPost::findOne($id);
		if($post === null) {
			throw new NotFoundException(404, YBoard::t('yboard', 'The requested post does not exist.'));
		}
        
        if(!Yii::$app->user->can('app.forum.forum.update', ['post'=>$post, 'isModerator'=>Yii::$app->user->can('moderator')]))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
         
		$topic = YBoardTopic::findOne($post->topic_id);
		$forum = YBoardForum::findOne($topic->forum_id);
        
		if(isset($_POST['YBoardPost'])) {
			$post->attributes = $_POST['YBoardPost'];
			$post->change_id = Yii::$app->user->id;
			if($forum->moderated) {
				$post->approved = 0;
			} else {
				$post->approved = 1;
			}
			if($post->save()) {
				if(!$post->approved) {
					$forum->updateCounters(['num_posts'=>-1]);					
					if($topic->num_replies > 0) {
						$topic->updateCounters(['num_replies'=>-1]);				
					} else {
						$topic->approved = 0;
						$topic->update();
						$forum->updateCounters(['num_topics'=>-1]);				
					}
					$post->poster->updateAllCounters(['posts'=>-1]);				
				}
				$this->redirect(['topic', 'id'=>$post->topic_id]);
			}
		}
		
        return $this->render('update', array(
			'forum' =>$forum,
			'topic' => $topic,
			'post' => $post
		));
	}
  
    /**
	 * Reply to a topic
	 * @param $id integer topic_id
	 */
	public function actionReply($id) {
        
        if(!Yii::$app->user->can('app.forum.forum.reply'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
         
		$topic = YBoardTopic::findOne($id);
		if($topic === null) { 
			throw new NotFoundHttpException(YBoard::t('yboard', 'The requested topic does not exist.'), 404);
		}
        
		if($topic->approved==0) { 
			throw new ForbiddenHttpException(YBoard::t('yboard', 'The requested topic cannot be replied to.'), 403);
		}
        
		$forum = YBoardForum::findOne($topic->forum_id);
		$post = new YBoardPost;
        
		if(isset($_POST['YBoardPost'])) {
			$post->attributes = $_POST['YBoardPost'];
			$post->user_id = Yii::$app->user->id;
			if($forum->moderated) {
				$post->approved = 0;
			} else {
				$post->approved = 1;
			}
            
            $post->forum_id = $topic->forum_id;
        
			if($post->save()) {
				if($post->approved) {
					$forum->updateCounters(['num_posts'=>1]);
                    
					$topic->updateCounters(['num_replies'=>1]);
                    
					$topic->updateAttributes(['last_post_id'=>$post->id]);
					$forum->updateAttributes(['last_post_id'=>$post->id]);
                    
				} else {
					Yii::$app->session->setFlash('moderation',YBoard::t('yboard', 'Your post has been saved. It has been placed in a queue and is now waiting for approval by a moderator before it will appear on the forum. Thank you for your contribution to the forum.'));
				}
                
                //send notifications
                $this->sendNotifications($post);
                
				$this->redirect(['topic', 'id'=>$post->topic_id, 'nav'=>'last', '#'=>$post->id]);
			}
		} else {
			$post->subject = $topic->title;
			$post->forum_id = $forum->id;
			$post->topic_id = $topic->id;
		}
        
		return $this->render('reply', array(
			'forum' => $forum,
			'topic' => $topic,
			'post' => $post
		));
	}
    
    /**
	 * Handle Ajax call for upvote/downvote of post
	 */
	public function actionUpvote() {
        if(!Yii::$app->user->can('app.forum.forum.upvote'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
         
		$json = [];
		if(isset($_POST['id'])) { 
            $criteria =  [
                'member_id'=>Yii::$app->user->identity->id,
                'post_id'=>$_POST['id']
            ]; 
                
            if( YBoardUpvoted::find()->where($criteria)->count()>0) {	// remove upvote
				YBoardUpvoted::deleteAll($criteria);
				
                $post = YBoardPost::findOne($_POST['id']);
				$topic = YBoardTopic::findOne($post->topic_id);
				$member = YBoardMember::findOne($post->user_id);
				
                $post->updateCounters(array('upvoted'=>-1));
				$topic->updateCounters(array('upvoted'=>-1)); 
			} else {										// add upvote
				$upvote = new YBoardUpvoted;
				$upvote->member_id = Yii::$app->user->id;
				$upvote->post_id = $_POST['id'];
				$upvote->author = $_POST['author'];
				$upvote->save();
				
                $post = YBoardPost::findOne($_POST['id']);
				$topic = YBoardTopic::findOne($post->topic_id);
				$member = YBoardMember::findOne($post->user_id);
				
                $post->updateCounters(array('upvoted'=>1));
				$topic->updateCounters(array('upvoted'=>1)); 
			}
			$json['success'] = 'yes';
			$json['html'] = $this->showUpvote($_POST['id']);
		} else {
			$json['success'] = 'no';
		}
		echo json_encode($json);
		Yii::$app->end();
	}
    
    
    /**
	 * Handle Ajax call for voting
	 */
	public function actionVote() {
        if(!Yii::$app->user->can('app.forum.forum.vote'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
         
		$json = [];
		if(isset($_POST['poll_id'])) {
			$this->poll = YBoardPoll::findOne($_POST['poll_id']);
			if(isset($_POST['choice'])) {
				// In case of a revote: remove previous votes
				$votes = YBoardVote::find()->where(['poll_id'=>$_POST['poll_id'], 'user_id'=>Yii::$app->user->id])->all();
				foreach($votes as $vote) {
					$this->poll->updateCounters(['votes'=>-1]);
					$model = YBoardChoice::findOne($vote->choice_id);
					$model->updateCounters(['votes'=>-1]);
					$vote->delete();
				}
				foreach($_POST['choice'] as $choice) {
					$model = new YBoardVote;
					$model->poll_id = $_POST['poll_id'];
					$model->choice_id = $choice;
					$model->user_id = Yii::$app->user->id;
					$model->save();
					$model = YBoardChoice::findOne($choice);
					$model->updateCounters(array('votes'=>1));
					$this->poll->updateCounters(array('votes'=>1));
				} 
                
                $provider = new ActiveDataProvider([
                    'query' => YBoardChoice::find()
                        ->where(['poll_id'=>$_POST['poll_id']])
                        ->orderBy('sort'),
					'pagination'=>false,
                ]);
                
				$json['html'] = $this->renderAjax('poll', array('choiceProvider'=>$provider), true);
				$json['success'] = 'yes';
			} else {
				$json['success'] = 'no';
			}
		} else {
			$json['success'] = 'no';
		}
		echo json_encode($json);
		Yii::$app->end();
	}
    
    /**
	 * Handle Ajax call for display of vote form
	 */
	public function actionDisplayVote() {
		$json = [];
		if(isset($_POST['poll_id'])) {
			$this->poll = YBoardPoll::findOne($_POST['poll_id']);
			$choiceProvider=new ActiveDataProvider([
                'query' => YBoardChoice::find()
                    ->where(['poll_id'=>$_POST['poll_id']])
                    ->orderBy('sort'),
                'pagination'=>false,
            ]); 
            
			$json['html'] = $this->renderPartial('vote', ['choiceProvider'=>$choiceProvider], true);
			$json['success'] = 'yes';
		} else {
			$json['success'] = 'no';
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * Handle Ajax call for display of poll edit form
	 */
	public function actionEditPoll() {
        
        if(!Yii::$app->user->can('app.forum.forum.edit-poll'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
         
		$json = array();
		if(isset($_POST['poll_id'])) {
			$poll = YBoardPoll::findOne($_POST['poll_id']);
			$choices = [];
			$models = YBoardChoice::find()->where(['poll_id' => $poll->id])->all();
			foreach($models as $model) {
				$choices[$model->id] = $model->choice;
			}
			$json['html'] = $this->renderAjax('editPoll',['poll'=>$poll, 'choices'=>$choices], true);
			$json['success'] = 'yes';
		} else {
			$json['success'] = 'no';
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	public function actionUpdatePoll($id) {
		$poll = YBoardPoll::findOne($id);
		if($poll === null) {
			throw new NotFoundHttpException(YBoard::t('yboard', 'The requested poll does not exist.'));
		}
         
        if(!Yii::$app->user->can('app.forum.forum.update-poll', ['poll'=>$poll, 'isModerator'=>Yii::$app->user->can('moderator')]))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
         
        
		$post = YBoardPost::findOne($poll->post_id);
		
        if($poll->user_id != Yii::$app->user->id && !Yii::$app->user->can('moderator')) {
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not authorized to perform this action.'));
		}
        
		if(isset($_POST['YBoardPoll'])) {
			$poll->attributes = $_POST['YBoardPoll'];
			if(empty($poll->expire_date)) {
				unset($poll->expire_date);
			}
			if($poll->save()) {
				$choices = $_POST['choice'];
				foreach($choices as $key => $choice) {
					$ch = YBoardChoice::findOne($key);
					if($ch !== null) {
						$ch->choice = $choice;
						$ch->save();
					}
				}
			}
		}
		$this->redirect(array('topic', 'id'=>$post->topic_id));
	} 
    
    /**
	 * Quote the original post in the reply (reply to a post)
	 * @param $id integer post_id
	 */
	public function actionQuote($id) {
        if(!Yii::$app->user->can('app.forum.forum.quote'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
         
		$quoted = YBoardPost::findOne($id);
		if($quoted === null) {
			throw new NotFoundHttpException(404, YBoard::t('yboard', 'The requested post does not exist.'));
		}
        
        if($quoted->topic->approved==0) { 
			throw new ForbiddenHttpException(YBoard::t('yboard', 'The requested topic cannot be replied to.'), 403);
		}
        
		$topic = YBoardTopic::findOne($quoted->topic_id);
		$forum = YBoardForum::findOne($topic->forum_id); 
        
		if(isset($_POST['YBoardPost'])) {
			$post = new YBoardPost;
			$post->attributes = $_POST['YBoardPost'];
			$post->user_id = Yii::$app->user->id;
            
			if($forum->moderated) {
				$post->approved = 0;
			} else {
				$post->approved = 1;
			}
            
            $post->forum_id = $topic->forum_id;
			
            if($post->save()) {
				if($post->approved) {
					$forum->updateCounters(['num_posts'=>1]);					
					$topic->updateCounters(['num_replies'=>1]);
                    					
					$topic->updateAttributes(['last_post_id'=>$post->id]);
					$forum->updateAttributes(['last_post_id'=>$post->id]);
				} else {
					Yii::$app->session->setFlash('moderation',YBoard::t('yboard', 'Your post has been saved. It has been placed in a queue and is now waiting for approval by a moderator before it will appear on the forum. Thank you for your contribution to the forum.'));
				}
                
                //send notifications
                $this->sendNotifications($post);
                
				$this->redirect(['topic', 'id'=>$post->topic_id, 'nav'=>'last']);
			}
		} else {
			$post = new YBoardPost;
			$quote = '<div class="quotation-header"><cite class="quotation-username">'.$quoted->poster->profile->username .' '. YBoard::t('yboard', 'Wrote:') .' </cite><cite class="quotation-date">'. DateTimeCalculation::medium($quoted->create_time).'</cite></div>';
			$quote = '<div class="quotation-header"><cite class="quotation-username">'.$quoted->poster->profile->username .' '. YBoard::t('yboard', 'wrote') .' </cite><cite class="quotation-date">'. DateTimeCalculation::medium($quoted->create_time).'</cite></div>';
			$post->content = '<blockquote class="quotation-content">'. $quote .' ' . $quoted->content . '</blockquote>  <p></p>';
			$post->subject  = $quoted->subject;
			$post->forum_id = $quoted->forum_id;
			$post->topic_id = $quoted->topic_id;
		}
		
        return $this->render('reply', array(
			'forum' => $forum,
			'topic' => $topic,
			'post' => $post
		));
	}

    /**
     * Finds the YBoardForum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return YBoardForum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = YBoardForum::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
	 * Function to send mails to Reply
	 */
	public function sendNotifications($postModel) 
    {
        $query = new \yii\db\Query;
        $rows = $query->select('user_id')
            ->from('YBoardPost')
            ->where(['topic_id'=>$postModel->topic_id])
            ->distinct()
            ->all(); 
            
        $users = [];
        foreach($rows as $row)
        {
            if($row['user_id'] == $postModel->poster->id)
            {
                continue; //don't send to author
            }
            
            $users[] = $row['user_id'];
        }
         
        $query = new \yii\db\Query;
        $userDetails = $query->select('id,fname, lname, email')
            ->from('Users')
            ->where(['in', 'id', $users])
            ->all();
            
        $subject = YBoard::t('yboard','{user} replied to {topic}', ['user'=>$postModel->poster->profile->username, 'topic'=>$postModel->topic->title]);
        
        foreach($userDetails as $user)
        {        
            $message = YBoard::t('yboard', '{user}, {replier} has just posted a reply to a topic that you have subscribed to titled "{title}".
            ----------------------------------------------------------------------
            {summary}
            ----------------------------------------------------------------------
            The topic with currrent replies can be found {url}', [
                'user'=>$user['fname'].' '.$user['lname'], 
                'replier'=>$postModel->poster->profile->username,
                'title'=>$postModel->topic->title,
                'summary'=>$postModel->content,
                'url'=>Html::a(YBoard::t('yboard', 'here'), Yii::$app->urlManager->createAbsoluteUrl([$this->module->id.'/forum/topic', 'id'=>$postModel->topic_id])),
            ]);
            //strip new line
            $message = str_replace(array("\r\r", "\n\n"), '', $message); 
            $message =  nl2br($message);
            
            try
           {
                $sent = Yii::$app->mailer->compose()
                    ->setTo($user['email'])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setSubject($subject)
                    ->setHtmlBody($message)
                    ->send();
                    
                if(!$sent)
                {
                    die("FAILED TO SEND MAIL");
                }
           }
           catch(\Exception $e)
           {
                echo $e->getMessage();
                die();
           }
        }
    }
    
    /**
	 * Function to determine whether a forum group should be hidden
	 */
	public function collapsed($id) 
    {
		if(isset(Yii::$app->request->cookies['yboardCollapsed'])) {
			$catString = Yii::$app->request->cookies['yboardCollapsed']->value;
			$catArray = explode('_', $catString);
			if(in_array($id, $catArray)) {
				return true;
			}
		}
		return false;
	}
    
    /**
	 * Determine whether a forum is completely read by a user
	 * @param integer forum id
	 * @return boolean
	 */
	public function forumIsRead($forum_id) {
		if(Yii::$app->user->isGuest) {
			return false;
		} else { 
			$models = YBoardTopic::find()
                ->where(['forum_id'=>$forum_id])
                ->orderBy('last_post_id DESC')
                ->limit(100)
                ->all();
			$result = true;
			
            foreach($models as $topic) {
				$topicLog = YBoardLogTopic::findOne(array('member_id'=>Yii::$app->user->id, 'topic_id'=>$topic->id));
				if($topicLog === null) {
					$result = false;
					break;
				} else {
					if($topic->last_post_id > $topicLog->last_post_id) {
						$result = false;
						break;
					}
				}
			}
			return $result;
		}
	}
    
    /**
	 * Determine the icon for a topic
	 */
	public function topicIcon($topic) {
		$img = 'topic';
		if($this->topicIsRead($topic->id)) {
			$img .= '2';
		} else {
			$img .= '1';
		}
		if($topic->global) {
			$img .= 'g';
		}
		if($topic->sticky) {
			$img .= 's';
		} 
		if(YBoardPoll::find()->where(['post_id'=>$topic->first_post_id])->one()) {
			$img .= 'p';
		}
		if($topic->locked) {
			$img .= 'l';
		}
		return $img;
	}
    
    /**
	 * Determine whether a topic is completely read by a user
	 * @param integer forum id
	 * @return boolean
	 */
	public function topicIsRead($topic_id) {
		if(Yii::$app->user->isGuest) {
			return false;
		} else {
			$topicLog = YBoardLogTopic::findOne(['member_id'=>Yii::$app->user->id, 'topic_id'=>$topic_id]);
			if($topicLog === null) {
				return false;
			} else {
                 
				$topic = Yii::$app->db->cache(function ($db) use($topic_id) {
                    return YBoardTopic::findOne($topic_id);
                }, 300);
                
                
				if($topic->last_post_id > $topicLog->last_post_id) {
					return false;
				} else {
					return true;
				}
			}
		}
	} 
    
    public function showUpvote($post_id) {
		$url = Yii::$app->urlManager->createAbsoluteUrl($this->module->id.'/forum/upvote');
		$post = YBoardPost::findOne($post_id);
		if($post === null || $post->user_id == Yii::$app->user->id) {
			return '';
		}
        
        
        $upvoted = YBoardUpvoted::find()
            ->where(['member_id'=>Yii::$app->user->identity->id])
            ->andWhere(['post_id'=>$post_id])
            ->count();

		if($upvoted>0) {
			$html = Html::button(YBoard::t('yboard', 'Downvote').' <span class="glyphicon glyphicon-chevron-down"></span>', ['class'=>'btn btn-sm btn-default', 'title'=>YBoard::t('yboard', 'Remove your appreciation'), 'id'=>'upvote_'.$post_id, 'style'=>'cursor:pointer;', 'onclick'=>'upvotePost(' . $post_id . ','  . $post->user_id .  ',"' . $url . '")']);
		} else {
			$html = Html::button(YBoard::t('yboard', 'Upvote').' <span class="glyphicon glyphicon-chevron-up"></span>', ['class'=>'btn btn-sm btn-default', 'title'=>YBoard::t('yboard', 'Appreciate this post'), 'id'=>'upvote_'.$post_id, 'style'=>'cursor:pointer;', 'onclick'=>'upvotePost(' . $post_id . ','  . $post->user_id . ',"' . $url . '")']);
		}
		return $html;
	}
    
    public function actionMarkAllRead() {
        if(!Yii::$app->user->can('app.forum.forum.mark-all-read'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
         
		$topics = YBoardTopic::find()->all();
		foreach($topics as $topic) {
			$topicLog = YBoardLogTopic::findOne(['member_id'=>Yii::$app->user->id, 'topic_id'=>$topic->id]);
			if($topicLog === null) {
				$topicLog = new YBoardLogTopic;
				$topicLog->member_id = Yii::$app->user->id;
				$topicLog->topic_id = $topic->id;
				$topicLog->forum_id = $topic->forum_id;
			}
			$topicLog->last_post_id = $topic->last_post_id;
			$topicLog->save();
		}
        
		$this->redirect(['index']);
	}
    
    /**
	 * Banned users Lands Here!
	 */
	/**
     * Displays a single YBoardBan model.
     * @param integer $id
     * @return mixed
     */
    public function actionBanned($id)
    {
        $model = YBoardBan::findOne($id);
        $user = YBoardMember::findOne($model->user_id);
        $isIP = true;
        
        if($user!==null)
            $isIP = false;
        
        if ($model == null) { 
            throw new NotFoundHttpException(YBoard::t('yboard','The requested Banned User does not exist.'));
        }
        
        $settings = YBoardSetting::find()->where(['key'=>'email'])->one();
        
        return $this->render('banned', [
            'model' => $model,
            'member' => $user,
            'isIp' => $isIP,
            'email' => $settings== null ? YBoard::t('board', 'no email') : $settings->value,
        ]);
    }
    
    /**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::$app->errorHandler->exception)
		{ 
			if(Yii::$app->request->isAjax)
				echo $error->getMessage() ;
			else
				return $this->render('error', ['exception'=>$error]);
		}
	}
}

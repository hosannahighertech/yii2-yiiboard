<?php

namespace app\modules\yboard\controllers;

use app\modules\yboard\YBoard;
use app\modules\yboard\components\DateTimeCalculation;


use app\modules\yboard\models\YBoardBanSearch;
use app\modules\yboard\models\YBoardTopic;
use app\modules\yboard\models\YBoardPost;
use app\modules\yboard\models\YBoardForum;
use app\modules\yboard\models\YBoardMessage;
use app\modules\yboard\models\YBoardMember;
use app\modules\yboard\models\YBoardBan;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use Yii;
use yii\data\ActiveDataProvider;

class ModeratorController extends \yii\web\Controller
{
    public $layout = "admin";
    /**
     * Lists all YBoardBan models.
     * @return mixed
     */
    public function actionIndex()
    { 
        if(!Yii::$app->user->can('app.forum.moderator.index'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $pendingTopics = YBoardTopic::find()->where(['approved'=>0])->count();        
        $pendingPosts = YBoardPost::find()->where(['approved'=>0])->count();
        
        $bannedUsers = YBoardBan::find()->activeScope()->userScope()->count();
        $bannedEmails = YBoardBan::find()->activeScope()->emailScope()->count();
        $bannedIps = YBoardBan::find()->activeScope()->ipScope()->count();
        
        return $this->render('index', [ 
            'pendingTopics'=>$pendingTopics,
            'pendingPosts'=>$pendingPosts,
            'bannedUsers'=>$bannedUsers,
            'bannedEmails'=>$bannedEmails,
            'bannedIps'=>$bannedIps,
        ]);
    } 

    //approve pending posts and topics
    public function actionApprove()
    {
        if(!Yii::$app->user->can('app.forum.moderator.approve'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $topics = new ActiveDataProvider([
            'query' => YBoardTopic::find()->where(['approved'=>0]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        
        $posts = new ActiveDataProvider([
            'query' => YBoardPost::find()->where(['approved'=>0]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        
        return $this->render('approve', [
            'topicsProvider' => $topics,
            'postsProvider' => $posts,
        ]);
    } 
    
    /** Approve Topic
     */
    public function actionApproveTopic($id)
    {
        if(!Yii::$app->user->can('app.forum.moderator.approve-topic'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        if(!Yii::$app->request->isAjax)
            Yii::$app->end();
             
        $json = [];
        $post = YBoardTopic::findOne($id);
        if($post==null)
        {
            $json['success'] = 'no';
            $json['error'] = YBoard::t('yboard', 'Could not approve Topic');
        } 
        else
        { 
            $post->approved =1;
            if($post->save())
            {
                $json['success'] = 'yes';            
            }
            else
            {
                $json['success'] = 'no';
                $json['error'] = YBoard::t('yboard', 'Could not approve Topic!');
            }
        }
        echo json_encode($json);
        Yii::$app->end();
    }
    
    /** Approve Post
     */
    public function actionApprovePost($id)
    {
        if(!Yii::$app->user->can('app.forum.moderator.approve-post'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        if(!Yii::$app->request->isAjax)
            Yii::$app->end();
             
        $json = [];
        $post = YBoardPost::findOne($id);
        if($post==null)
        {
            $json['success'] = 'no';
            $json['error'] = YBoard::t('yboard', 'Could not approve Post');
        } 
        else
        { 
            $post->approved =1;
            if($post->save())
            {
                $json['success'] = 'yes';            
            }
            else
            {
                $json['success'] = 'no';
                $json['error'] = YBoard::t('yboard', 'Could not approve Post!');
            }
        }
        echo json_encode($json);
        Yii::$app->end();
    }
    
    /**
     * Lists all YBoardBan models.
     * @return mixed
     */
    public function actionBans()
    {
        if(!Yii::$app->user->can('app.forum.moderator.bans'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $searchModel = new YBoardBanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('ban', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /** Ban Specific User 
     */
    public function actionBanUser($id)
    {
        if(!Yii::$app->user->can('app.forum.moderator.ban-user'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        if(!Yii::$app->request->isAjax)
            Yii::$app->end();
             
        $json = [];
        $user = YBoardBan::find()->where(['user_id'=>$id])->one();
        if($user==null) 
             $user = new YBoardBan; 
        
        $attribs = $_POST;
        $attribs['expires'] = strtotime($attribs['expires']);
        $attribs['user_id'] = $id;
         
        $user->attributes= $attribs;
        
        if($user->save())
        {
            $json['success'] = 'yes';            
        }
        else
        {
            $json['success'] = 'no';
            $json['message'] = YBoard::t('yboard', 'Could Not Ban User!');
        }
        echo json_encode($json);
        Yii::$app->end();
        
    }
    
    /** Change Ban Time
     */
    public function actionChangeBanPeriod($id)
    {
        if(!Yii::$app->user->can('app.forum.moderator.change-ban-period'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        if(!Yii::$app->request->isAjax)
            Yii::$app->end(); 
        
        if(isset($_POST['hasEditable']) && isset($_POST['expires'])) { 
            
            $attrib = $_POST['expires'];
            $attrib = strtotime($attrib);   
                           
            $model = YBoardBan::findOne($id); 
            if($model==null)
            {
                echo json_encode(['output'=>'', 'message'=>'Could Not Update Field']);
                Yii::$app->end(); 
            }
            
            $model->expires = $attrib;
            
            if (!$model->save()) {
                // validation error
                echo json_encode(['output'=>'', 'message'=>'Could Not Update Field']);
            }
            // else if nothing to do always return an empty JSON encoded output
            else { 
                // read or convert your posted information
                $value = DateTimeCalculation::short($model->expires);
                echo json_encode(['output'=>$value, 'message'=>'']);
            }
        }
        else
        {
            throw new ForbiddenHttpException(YBoard::t('yboard','This Action is forbidden!'));
        }
    }

    /** Lift Ban
     */
    public function actionBanLift($id)
    {
        if(!Yii::$app->user->can('app.forum.moderator.ban-lift'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        if(!Yii::$app->request->isAjax)
            Yii::$app->end();
             
        $json = [];
        $ban = YBoardBan::findOne($id);
        $ban->setAttribute('expires', time()-60);//expired one minute ago
        if($ban!=null && $ban->save())  
        {
            $json['success'] = 'yes';  
        }
        else
        {
            $json['success'] = 'no';
            $json['error'] = YBoard::t('yboard', 'Could Not Lift the Ban!');
        } 
        echo json_encode($json);
        Yii::$app->end();
        
    }

    /**
	 * Ajax call for change, move or merge topic
	 */
	public function actionChangeTopic()
    {
        if(!Yii::$app->user->can('app.forum.moderator.change-topic'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        $json = []; 
		if(isset($_POST['YBoardTopic'])) {
			$model = YBoardTopic::findOne($_POST['YBoardTopic']['id']);
			$move = false;
			$merge = false;
			$sourceTopicId = $_POST['YBoardTopic']['id'];
			$sourceForumId = $model->forum_id;
			if($model->forum_id != $_POST['YBoardTopic']['forum_id']) {
				$move = true;
				$targetForumId = $_POST['YBoardTopic']['forum_id'];
			}
			if(!empty($_POST['YBoardTopic']['merge']) && $_POST['YBoardTopic']['id'] != $_POST['YBoardTopic']['merge']) {
				$merge = true;
				$targetTopicId = $_POST['YBoardTopic']['merge'];
			}
			$model->attributes=$_POST['YBoardTopic'];
			if($model->validate()) {
				$json['success'] = 'yes';
				if($merge || $move) {               
                    $criteria = "topic_id = $sourceTopicId";
					$numberOfPosts = YBoardPost::find()->approvedScope()->count();
					if($move) {     
						YBoardPost::updateAll(['forum_id'=>$targetForumId], $criteria);
                        
						$forum = YBoardForum::findOne($sourceForumId);
						$forum->updateAllCounters(['num_topics'=>-1]);
						$forum->updateAllCounters(['num_posts'=>-$numberOfPosts]);
						
                        $forum = YBoardForum::findOne($targetForumId);
						$forum->updateAllCounters(['num_topics'=>1]);
						$forum->updateAllCounters(['num_posts'=>$numberOfPosts]);
						
                        $this->resetLastForumPost($sourceForumId);
						$this->resetLastForumPost($targetForumId);
					}
					if($merge) {
						YBoardPost::updateAll(['topic_id'=>$targetTopicId], $criteria);
						if($move) {
							$forum = YBoardForum::findOne($targetForumId);
						} else {
							$forum = YBoardForum::findOne($sourceForumId);
						}
						$forum->updateCounters(array('num_topics'=>-1));
						$topic = YBoardTopic::findOne($targetTopicId);
						$topic->updateCounters(array('num_replies'=>$numberOfPosts));
						$model->delete();
					} else {
						$model->save();
					}
				} else {	// no move or merge involved
					$model->save();
				}
			} else {
                if(!$model->validate())
                    $json['error'] = $model->errors;
			}
		}
		echo json_encode($json);
		Yii::$app->end();
    }

    /**
	 * Delete a post
	 */
	public function actionDelete($id) {
        if(!Yii::$app->user->can('app.forum.moderator.delete-post'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		if(isset($_GET['id']))
			$id = $_GET['id'];
            
		$post = YBoardPost::findOne($id);
		if($post === null) {
			throw new NotFoundHttpException(YBoard::t('yboard', 'The requested post does not exist.'));
		}
		$forum = YBoardForum::findOne(YBoardTopic::findOne($post->topic_id)->forum_id);
        
        if($post->original_post==1) //main topic, delete all including replies
        {
            $postDeleted = 0;
            $modelsToDelete = YBoardPost::find()->where(['topic_id'=>$post->topic_id])->all();
            foreach($modelsToDelete as $modelDel)
            {
                $pid = $modelDel->post_id; 
                
                if($modelDel->delete())
                {
                    $postDeleted = $postDeleted-1;
                    YBoardMessage::deleteAll(['post_id'=>$pid]); //delete all reports
                }
            }
            //decrement counts
            $forum->updateCounters(['num_topics'=>$postDeleted]);	            
            YBoardLogTopic::deleteAll(['topic_id'=>$post->topic_id]);  //delete record in log
            $topic = YBoardTopic::findOne($post->topic_id)->delete();
        }
        else 
        {
            $forum->updateCounters(['num_posts'=>-1]);  
            $post->delete();           
            //Delete reports on the deleted post 
            YBoardMessage::deleteAll(['post_id'=>$id]);
        }
         
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl'])? $_POST['returnUrl'] : ['approval']);
		else
            echo  json_encode(['success'=>true]);
	}  
 
    /**
	 * Disapprove a post
	 */
	public function actionDisapprove($id) {
        if(!Yii::$app->user->can('app.forum.moderator.delete-post'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		if(isset($_GET['id']))
			$id = $_GET['id'];
            
		$post = YBoardPost::findOne($id);
		if($post === null) {
			throw new NotFoundHttpException(YBoard::t('yboard', 'The requested post does not exist.'));
		}     
        $post->approved = 0;   
        $post->save();  
         
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl'])? $_POST['returnUrl'] : ['approval']);
		else
            echo  json_encode(['success'=>true]);
	}  
 

    public function actionReported()
    {
        if(!Yii::$app->user->can('app.forum.moderator.reported'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
 
        $count = [];
        
		
        $query = YBoardMessage::find()
            ->inboxScope() 
            ->reportMsgScope();
          
        $count['reported'] = $query->count();  

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $this->render('report', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'count'=>$count
        ]);	  
    } 

    public function actionTopic() {
        if(!Yii::$app->user->can('app.forum.moderator.topic'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];
		if(isset($_POST['id'])) {
			$model = YBoardTopic::findOne($_POST['id']);
			if($model === null) {
				$json['success'] = 'no';
				$json['message'] = YBoard::t('yboard', 'Topic not found.');
			} else {
				$json['success'] = 'yes';
				$json['forum_id'] = $model->forum_id;
				$json['title'] = $model->title;
				$json['locked'] = $model->locked;
				$json['sticky'] = $model->sticky;
				$json['global'] = $model->global;
				$json['approved'] = $model->approved;
				$json['option'] = '<option value=""></option>';
				foreach(YBoardTopic::find()
                ->where(['forum_id' => $model->forum_id])
                ->all() as $topic) {
					$json['option'] .= '<option value="' . $topic->id. '">' . $topic->title . '</option>';
				}
			}
		} else {
			$json['success'] = 'no';
			$json['message'] = YBoard::t('yboard', 'Topic not found.');
		}
	
		echo json_encode($json);
		Yii::$app->end();
	}

    public function actionGetPost($id)
    {
        if(!Yii::$app->user->can('app.forum.moderator.get-post'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
        if(!Yii::$app->user->can('app.forum.moderator.delete-post'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];
        $model = YBoardPost::findOne($id);
        if($model==null)
        {
			$json['success'] = 'no';
			$json['error'] = YBoard::t('yboard', 'Post not found.');
        }
        else
        {
            $html = $this->renderAjax('_post', ['post'=>$model]);
            $json['success'] = 'yes';
            $json['html'] = $html;
        }
		echo json_encode($json);
		Yii::$app->end();
    } 
    
    public function actionChangePost()
    {
        if(!Yii::$app->user->can('app.forum.moderator.change-post'))
            throw new ForbiddenHttpException(YBoard::t('yboard', 'You have no enough permission to access this page! If you think its a mistake, please consider reporting to us.'));
        
		$json = [];
        $id = $_POST['YBoardPost']['id']; 
        
        $model = YBoardPost::findOne($id);
        
        if($model==null)
        {
			$json['success'] = 'no';
			$json['error'] = YBoard::t('yboard', 'Post not found.');
        }
        else
        {
            $model->attributes = $_POST['YBoardPost'];
            $model->change_reason = YBoard::t('yboard', 'Post modearated by {user}', ['user'=>YBoardMember::findOne(Yii::$app->user->id)->profile->username]);
            
            if($model->save())
            {
                $json['success'] = 'yes'; 
            }
            else
            {
                $json['success'] = 'no';
                $json['error'] = YBoard::t('yboard', 'Post was not updated.');
            }
        }
		echo json_encode($json);
		Yii::$app->end();
    } 
    
    /* 
     * Reset the last post of a forum
	 */
	private function resetLastForumPost($id) {
		$model = YBoardForum::findOne($id); 
		$criteria = "forum_id = $id and approved = 1"; 
		$post = YBoardPost::find()
            ->where($criteria)
            ->orderBy('id DESC')
            ->one();
            
		if($post !== null) {
			$model->last_post_id = $post->id;
		} else {
			$model->last_post_id = null;
		}
		$model->save();
	} 
}

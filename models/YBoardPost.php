<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;
use app\modules\yboard\components\Utils;
use yii\db\Query;

/**
 * This is the model class for table "yboard_post".
 *
 * @property string $id
 * @property string $subject
 * @property string $content
 * @property string $user_id
 * @property string $topic_id
 * @property string $forum_id
 * @property string $original_post
 * @property string $ip
 * @property string $create_time
 * @property integer $approved
 * @property string $change_id
 * @property string $change_time
 * @property string $change_reason
 * @property integer $upvoted
 *
 * @property YBoardLogTopic[] $yboardLogTopics
 * @property YBoardMessage[] $yboardMessages
 * @property YBoardPoll[] $yboardPolls
 * @property YBoardForum $forum
 * @property YBoardPost $topic
 * @property YBoardPost[] $yboardPosts
 * @property YBoardMember $user
 * @property YBoardUpvoted[] $yboardUpvoteds
 */
class YBoardPost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardPost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'content'], 'required'],
            [['content'], 'string'],
            [['user_id', 'topic_id', 'forum_id', 'approved', 'change_id', 'upvoted'], 'integer'],
            [['create_time', 'original_post','change_time'], 'safe'],
            [['subject', 'change_reason'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 39]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'subject' => YBoard::t('yboard', 'Subject'),
            'content' => YBoard::t('yboard', 'Content'),
            'user_id' => YBoard::t('yboard', 'User ID'),
            'topic_id' => YBoard::t('yboard', 'Topic ID'),
            'forum_id' => YBoard::t('yboard', 'Forum ID'),
            'ip' => YBoard::t('yboard', 'Ip'),
            'create_time' => YBoard::t('yboard', 'Create Time'),
            'approved' => YBoard::t('yboard', 'Approved'),
            'change_id' => YBoard::t('yboard', 'Change ID'),
            'change_time' => YBoard::t('yboard', 'Change Time'),
            'change_reason' => YBoard::t('yboard', 'Change Reason'),
            'upvoted' => YBoard::t('yboard', 'Upvoted'),
            'original_post' => YBoard::t('yboard', 'Original Post'),
        ];
    }
    
    public function beforeSave($insert)
    { 
        if($this->isNewRecord){
            $this->create_time = time(); // new \yii\db\Expression('NOW()');
        } 
        else
        {
            $this->change_time = time(); //new \yii\db\Expression('NOW()');
        }
        
        $this->content = Utils::cleanHtml($this->content);
        
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogTopics()
    {
        return $this->hasMany(YBoardLogTopic::className(), ['last_post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(YBoardMessage::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolls()
    {
        return $this->hasMany(YBoardPoll::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForum()
    {
        return $this->hasOne(YBoardForum::className(), ['id' => 'forum_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(YBoardTopic::className(), ['id' => 'topic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(YBoardPost::className(), ['topic_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoster()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpvotes()
    {
        return $this->hasMany(YBoardUpvoted::className(), ['post_id' => 'id']);
    }
    
    /**
     * @inheritdoc
     * @return YBoardForumQuery
     */
    public static function find()
    {
        return new \app\modules\yboard\models\YBoardPostQuery(get_called_class());
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        $this->resetRank(); // post added, change user rank to reflect post count
        
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            //delete all upvotes and polls
            YBoardUpvoted::deleteAll(['post_id'=>$this->id]);
            YBoardPoll::deleteAll(['post_id'=>$this->id]);
            
            return true;
        } else {
            return false;
        }
    }
    
    public function afterDelete()
    {
        $this->resetRank(); // post deleted, change user rank to reflect post count
        $this->resetLastPost();
        $this->resetFirstTopicPost();
         
        return parent::afterDelete();
    }
    
    protected function resetRank()
    {
        $author = YBoardMember::findOne($this->user_id);
        if($author!==null)
        {
            $query = new Query; 
            $query->select('id')
                ->from('YBoardRank')
                ->orderBy('min_posts ASC')
                ->where('min_posts>='.$author->totalReplies)
                ->limit(1);
                           
            $count = $query->count();
            $id = $query->one();
            
            if($author->rank_id!=$id['id'] && $count>0)
            {
                $author->setAttribute('rank_id', $id['id']);
                $author->save();
            }
        }
    }
    
    /**
	 * Reset the last post of a topic and a forum when post is deleted
	 */
	private function resetLastPost() { 
		$forum = YBoardForum::find()->where(['last_post_id'=>$this->id])->one();
		$topic = YBoardTopic::find()->where(['last_post_id'=>$this->id])->one();

		if($forum !== null) { 
			$post = YBoardPost::find()
            ->where(['forum_id'=>$forum->id, 'approved'=>1])
            ->orderBy('id DESC')
            ->limit(1) 
            ->one();
            
			if($post === null) {
				$forum->last_post_id = null;
			} else {
				$forum->last_post_id = $post->id;
			}
			$forum->update();
		}
        
		if($topic !== null) {
			$post = YBoardPost::find()
            ->where(['topic_id'=>$topic->id, 'approved'=>1])
            ->orderBy('id DESC')
            ->limit(1) 
            ->one();
            
			if($post === null) {
				$topic->last_post_id = null;
			} else {
				$topic->last_post_id = $post->id;
			}
			$topic->update();
		}
	} 
    
    /**
	 * Reset the first post id of a topic when a first post is deleted
	 */
	private function resetFirstTopicPost() { 
		$model = YBoardTopic::find()
        ->where(['first_post_id'=>$this->id])
        ->one();
        
		if($model !== null) { 
			$post = YBoardPost::find()
            ->where(['topic_id'=>$model->id])
            ->orderBy('id DESC') 
            ->one();
            
			if($post !== null) {
				$model->user_id = $post->user_id;
				$model->first_post_id = $post->id;
				$model->save();
			}
		}
	}
    
}

<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_topic".
 *
 * @property string $id
 * @property string $forum_id
 * @property string $user_id
 * @property string $title
 * @property string $first_post_id
 * @property string $last_post_id
 * @property string $num_replies
 * @property string $num_views
 * @property integer $approved
 * @property integer $locked
 * @property integer $sticky
 * @property integer $global
 * @property string $moved
 * @property integer $upvoted
 *
 * @property YBoardLogTopic $yboardLogTopic
 * @property YBoardMember[] $members
 * @property YBoardForum $forum
 */
class YBoardTopic extends \yii\db\ActiveRecord
{
	public $merge;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardTopic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['forum_id', 'title', 'first_post_id', 'last_post_id'], 'required'],
            [['forum_id', 'user_id', 'first_post_id', 'last_post_id', 'num_replies', 'num_views', 'approved', 'locked', 'sticky', 'global', 'moved', 'upvoted'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'forum_id' => YBoard::t('yboard', 'Forum ID'),
            'user_id' => YBoard::t('yboard', 'User ID'),
            'title' => YBoard::t('yboard', 'Title'),
            'first_post_id' => YBoard::t('yboard', 'First Post ID'),
            'last_post_id' => YBoard::t('yboard', 'Last Post ID'),
            'num_replies' => YBoard::t('yboard', 'Replies'),
            'num_views' => YBoard::t('yboard', 'Views'),
            'approved' => YBoard::t('yboard', 'Approved'),
            'locked' => YBoard::t('yboard', 'Locked'),
            'sticky' => YBoard::t('yboard', 'Sticky'),
            'global' => YBoard::t('yboard', 'Global'),
            'moved' => YBoard::t('yboard', 'Moved'),
            'upvoted' => YBoard::t('yboard', 'Upvoted'),
			'merge' => YBoard::t('yboard', 'Merge with topic'),
        ];
    }
    
    /**
	 * Returns the css class when a member has posted in a topic
	 */
	public function hasPostedClass() {
		if(!\Yii::$app->user->isGuest && YBoardPost::find()->where("topic_id = $this->id and user_id = ".\Yii::$app->user->id)->exists()) {
			return 'posted';
		}
		return '';
	} 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogTopic()
    {
        return $this->hasOne(YBoardLogTopic::className(), ['topic_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(YBoardMember::className(), ['id' => 'member_id'])->viaTable('yboard_log_topic', ['topic_id' => 'id']);
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
    public function getStarter()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirstPost()
    {
        return $this->hasOne(YBoardPost::className(), ['id' => 'first_post_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastPost()
    {
        return $this->hasOne(YBoardPost::className(), ['id' => 'last_post_id']);
    }
    
    public function beforeSave($insert)
    { 
        $this->user_id = \Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
    
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            //delete all upvotes and polls
            YBoardPost::deleteAll(['topic_id'=>$this->id]);
            YBoardLogTopic::deleteAll(['topic_id'=>$this->id]);
            
            return true;
        } else {
            return false;
        }
    }
    
}

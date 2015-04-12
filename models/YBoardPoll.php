<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_poll".
 *
 * @property string $id
 * @property string $question
 * @property string $post_id
 * @property string $user_id
 * @property string $expire_date
 * @property integer $allow_revote
 * @property integer $allow_multiple
 * @property integer $votes
 *
 * @property YBoardMember $user
 * @property YBoardPost $post
 * @property YBoardVote $yboardVote
 */
class YBoardPoll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardPoll';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'post_id', 'user_id'], 'required'],
            [['post_id', 'user_id', 'allow_revote', 'allow_multiple', 'votes'], 'integer'],
            [['expire_date'], 'safe'],
            [['question'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'question' => YBoard::t('yboard', 'Question'),
            'post_id' => YBoard::t('yboard', 'Post ID'),
            'user_id' => YBoard::t('yboard', 'User ID'),
            'expire_date' => YBoard::t('yboard', 'Expire Date'),
            'allow_revote' => YBoard::t('yboard', 'Allow Revote'),
            'allow_multiple' => YBoard::t('yboard', 'Allow Multiple'),
            'votes' => YBoard::t('yboard', 'Votes'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(YBoardPost::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVote()
    {
        return $this->hasOne(YBoardVote::className(), ['poll_id' => 'id']);
    }
    
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            //delete all upvotes and polls
            YBoardUpvoted::deleteAll(['poll_id'=>$this->id]); 
            
            return true;
        } else {
            return false;
        }
    }
    
}

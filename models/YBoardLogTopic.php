<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_log_topic".
 *
 * @property string $member_id
 * @property string $topic_id
 * @property string $forum_id
 * @property string $last_post_id
 *
 * @property YBoardMember $member
 * @property YBoardForum $forum
 * @property YBoardPost $lastPost
 * @property YBoardTopic $topic
 */
class YBoardLogTopic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardLogTopic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'topic_id', 'forum_id', 'last_post_id'], 'required'],
            [['member_id', 'topic_id', 'forum_id', 'last_post_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => YBoard::t('yboard', 'Member ID'),
            'topic_id' => YBoard::t('yboard', 'Topic ID'),
            'forum_id' => YBoard::t('yboard', 'Forum ID'),
            'last_post_id' => YBoard::t('yboard', 'Last Post ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'member_id']);
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
    public function getLastPost()
    {
        return $this->hasOne(YBoardPost::className(), ['id' => 'last_post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(YBoardTopic::className(), ['id' => 'topic_id']);
    }
}

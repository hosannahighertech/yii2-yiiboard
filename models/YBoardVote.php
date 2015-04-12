<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_vote".
 *
 * @property integer $poll_id
 * @property integer $choice_id
 * @property integer $user_id
 *
 * @property YBoardChoice $choice
 * @property YBoardPoll $poll
 * @property YBoardMember $user
 */
class YBoardVote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardVote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['poll_id', 'choice_id', 'user_id'], 'required'],
            [['poll_id', 'choice_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'poll_id' => YBoard::t('yboard', 'Poll ID'),
            'choice_id' => YBoard::t('yboard', 'Choice ID'),
            'user_id' => YBoard::t('yboard', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChoice()
    {
        return $this->hasOne(YBoardChoice::className(), ['id' => 'choice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoll()
    {
        return $this->hasOne(YBoardPoll::className(), ['id' => 'poll_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'user_id']);
    }
}

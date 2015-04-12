<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_choice".
 *
 * @property string $id
 * @property string $choice
 * @property string $poll_id
 * @property integer $sort
 * @property integer $votes
 *
 * @property YBoardVote $yboardVote
 */
class YBoardChoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardChoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['choice', 'poll_id'], 'required'],
            [['poll_id', 'sort', 'votes'], 'integer'],
            [['choice'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'choice' => YBoard::t('yboard', 'Choice'),
            'poll_id' => YBoard::t('yboard', 'Poll ID'),
            'sort' => YBoard::t('yboard', 'Sort'),
            'votes' => YBoard::t('yboard', 'Votes'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVote()
    {
        return $this->hasOne(YBoardVote::className(), ['choice_id' => 'id']);
    }
    
    public function beforeDelete()
    {
        if (parent::beforeDelete()) { 
            
            YBoardVote::deleteAll(['choice_id'=>$this->id]); 
                     
            return true;
        } else {
            return false;
        }
    }
}

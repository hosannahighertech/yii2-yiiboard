<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_upvoted".
 *
 * @property string $member_id
 * @property string $post_id
 *
 * @property YBoardMember $member
 * @property YBoardPost $post
 */
class YBoardUpvoted extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardUpvoted';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'post_id'], 'required'],
            [['member_id', 'post_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => YBoard::t('yboard', 'Member ID'),
            'post_id' => YBoard::t('yboard', 'Post ID'),
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
    public function getPost()
    {
        return $this->hasOne(YBoardPost::className(), ['id' => 'post_id']);
    }
}

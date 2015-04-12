<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "YBoardRank".
 *
 * @property integer $id
 * @property string $title
 * @property integer $min_posts
 * @property integer $stars
 *
 * @property YBoardMember[] $yBoardMembers
 */
class YBoardRank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardRank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'min_posts'], 'required'],
            [['min_posts', 'stars'], 'integer'],
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
            'title' => YBoard::t('yboard', 'Title'),
            'min_posts' => YBoard::t('yboard', 'Min Posts'),
            'stars' => YBoard::t('yboard', 'Stars'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYBoardMembers()
    {
        return $this->hasMany(YBoardMember::className(), ['rank_id' => 'id']);
    }
}

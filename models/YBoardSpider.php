<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_spider".
 *
 * @property string $id
 * @property string $name
 * @property string $user_agent
 * @property string $hits
 * @property string $last_visit
 */
class YBoardSpider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardSpider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_agent', 'last_visit'], 'required'],
            [['hits'], 'integer'],
            [['last_visit'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['user_agent'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'name' => YBoard::t('yboard', 'Name'),
            'user_agent' => YBoard::t('yboard', 'User Agent'),
            'hits' => YBoard::t('yboard', 'Hits'),
            'last_visit' => YBoard::t('yboard', 'Last Visit'),
        ];
    }
    
    
	
	public function getUrl() {
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $this->user_agent, $match);
		if(isset($match[0][0])) {
			return $match[0][0];
		} else {
			return '';
		}
	}
    
    /**
     * @inheritdoc
     * @return YBoardSpiderQuery
    */
    public static function find()
    {
        return new YBoardSpiderQuery(get_called_class());
    }
}

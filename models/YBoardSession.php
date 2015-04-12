<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_session".
 *
 * @property string $id
 * @property string $last_visit
 */
class YBoardSession extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardSession';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'last_visit'], 'required'],
            [['user_id'], 'safe'],
            [['id'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'last_visit' => YBoard::t('yboard', 'Last Visit'),
        ];
    }
    
    /**
     * @inheritdoc
     * @return YBoardMemberQuery
     */
    public static function find()
    {
        return new YBoardSessionQuery(get_called_class());
    }
}

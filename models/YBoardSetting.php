<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_setting".
 *
 * @property string $id
 * @property string $key
 * @property string $value
 */
class YBoardSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardSetting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key','value'], 'required'],
            [['key'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'key' => YBoard::t('yboard', 'Key'),
            'value' => YBoard::t('yboard', 'Value'),
        ];
    }
}

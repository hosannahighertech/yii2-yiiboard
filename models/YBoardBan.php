<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "YBoardBan".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property string $email
 * @property string $message
 * @property integer $expires
 * @property integer $banned_by
 *
 * @property YBoardMember $bannedBy
 * @property YBoardMember $user
 */
class YBoardBan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardBan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'banned_on', 'expires', 'banned_by'], 'integer'],
            [['banned_by'], 'required'],
            [['ip', 'message'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'user_id' => YBoard::t('yboard', 'Member'),
            'ip' => YBoard::t('yboard', 'IP'),
            'email' => YBoard::t('yboard', 'Email'),
            'message' => YBoard::t('yboard', 'Reason'),
            'expires' => YBoard::t('yboard', 'Expires'),
            'banned_by' => YBoard::t('yboard', 'Banned By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannedByMe()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'banned_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanner()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'banned_by']);
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) 
        {
            $this->banned_on = time();
            
            return true;
        } 
        else 
        {
            return false;
        }
    }
    
    /**
     * @inheritdoc
     * @return YBoardForumQuery
     */
    public static function find()
    {
        return new YBoardBanQuery(get_called_class());
    }
}

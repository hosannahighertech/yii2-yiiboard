<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;
use app\modules\yboard\components\Utils;

/**
 * This is the model class for table "yboard_message".
 *
 * @property integer $id
 * @property integer $sendfrom
 * @property integer $sendto
 * @property integer $post_id
 * @property string $subject
 * @property string $content
 * @property string $create_time
 * @property integer $read_indicator
 * @property integer $type
 * @property integer $inbox
 * @property integer $outbox
 * @property string $ip
 *
 * @property YBoardMember $sendfrom0
 * @property YBoardMember $sendto0
 */
class YBoardMessage extends \yii\db\ActiveRecord
{
    public $usernames = null;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardMessage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [ 
            [['sendfrom', 'sendto', 'subject', 'content'], 'required'],
            [['id', 'sendfrom', 'sendto', 'post_id', 'read_indicator', 'type', 'inbox', 'outbox'], 'integer'],
            [['content', 'usernames'], 'string'],
            [['create_time'], 'safe'],
            [['subject'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 39],
            [['ip'], 'blocked'],
            [['sendto'], 'mailboxFull', 'on'=>'insert'], 

        ];
    }
    
    public function blocked($attribute, $params) {
		if(YBoardIpaddress::blocked($this->ip)) {
			$this->addError('ip', YBoard::t('yboard','Your IP address has been blocked.'));
		}
	}
    
    public function mailboxFull($attr, $params) { 
		$criteria = ['outbox'=> 1, 'sendfrom' =>Yii::$app->user->id];
		if(YBoardMessage::find()->where($criteria)->outbox()->count() >=Yii::$app->params['maxMessages']) {
			$this->addError('sendto', YBoard::t('yboard', 'Your outbox is full. Please make room before sending new messages.'));
		}
	}


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'sendfrom' => YBoard::t('yboard', 'From'),
            'sendto' => YBoard::t('yboard', 'To'),
            'post_id' => YBoard::t('yboard', 'Post'),
            'subject' => YBoard::t('yboard', 'Subject'),
            'content' => YBoard::t('yboard', 'Contents'),
            'create_time' => YBoard::t('yboard', 'Time'),
            'read_indicator' => YBoard::t('yboard', 'Read Indicator'),
            'type' => YBoard::t('yboard', 'Type'),
            'inbox' => YBoard::t('yboard', 'Inbox'),
            'outbox' => YBoard::t('yboard', 'Outbox'),
            'ip' => YBoard::t('yboard', 'IP Address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'sendfrom']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(YBoardMember::className(), ['id' => 'sendto']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(YBoardPost::className(), ['id' => 'post_id']);
    }

    /**
     * @inheritdoc
     * @return YBoardForumQuery
     */
    public static function find()
    {
        return new YBoardMessageQuery(get_called_class());
    }
    
    public function beforeSave($insert)
    {         
        $this->content = Utils::cleanHtml($this->content);
        
        return parent::beforeSave($insert);
    }

}

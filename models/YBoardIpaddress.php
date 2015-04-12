<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "yboard_ipaddress".
 *
 * @property string $id
 * @property string $ip
 * @property string $address
 * @property integer $source
 * @property integer $count
 * @property string $create_time
 * @property string $update_time
 */
class YBoardIpaddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardIPAddress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source', 'count'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['ip'], 'string', 'max' => 39],
            [['address'], 'string', 'max' => 255],
            [['ip'], 'unique'],
        ];
    }
    
    public static function blocked($ip) {
		$model = YBoardIpaddress::find()->where(['ip' => $ip])->one();
		if($model === null) {
			return false;
		} else {
			$model->updateCounters(['count'=>1]);					// method since Yii 1.1.8
			return true;
		}
	}
    
    public function beforeValidate() {
		if(strlen($this->ip) > 0 && $this->address == '') {
			$this->address = gethostbyaddr($this->ip);
		}
		return parent::beforeValidate();
	}
    
    public function beforeSave($insert) {
        $this->create_time = new \yii\db\Expression('NOW()');
        $this->update_time = new \yii\db\Expression('NOW()');
        $this->source = $this->count = 0;

		return parent::beforeSave($insert);
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'ID'),
            'ip' => YBoard::t('yboard', 'Ip'),
            'address' => YBoard::t('yboard', 'Address'),
            'source' => YBoard::t('yboard', 'Source'),
            'count' => YBoard::t('yboard', 'Count'),
            'create_time' => YBoard::t('yboard', 'Create Time'),
            'update_time' => YBoard::t('yboard', 'Update Time'),
        ];
    }
}

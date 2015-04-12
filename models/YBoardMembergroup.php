<?php

namespace app\modules\yboard\models;

use yii\data\ActiveDataProvider;
use Yii;
use app\modules\yboard\YBoard;

/**
 * This is the model class for table "YBoardMemberGroup".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $color
 * @property string $image
 * @property string $group_role
 *
 * @property YBoardMember[] $yBoardMembers
 */
class YBoardMembergroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardMemberGroup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 45],
            [['color'], 'string', 'max' => 7],
            [['image'], 'string', 'max' => 255],
            [['group_role'], 'string', 'max' => 64]
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
            'description' => YBoard::t('yboard', 'Description'),
            'color' => YBoard::t('yboard', 'Color'),
            'image' => YBoard::t('yboard', 'Image'),
            'group_role' => YBoard::t('yboard', 'Group Role'),
        ];
    }
    
    public function search()
    {
        $query = YBoardMembergroup::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        //if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
       // }

        //$query->andFilterWhere([
        //    'id' => $this->id,
        //]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(YBoardMember::className(), ['group_id' => 'id']);
    }
    
     /**
     * @inheritdoc
     * @return YBoardMemberQuery
    */
    public static function find()
    {
        return new YBoardMembergroupQuery(get_called_class());
    }
}

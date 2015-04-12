<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard; 

/**  
 * This is the model class for table "yboard_forum".
 *
 * @property string $id
 * @property string $cat_id
 * @property string $name
 * @property string $subtitle
 * @property integer $type
 * @property integer $public
 * @property integer $locked
 * @property integer $moderated
 * @property integer $sort
 * @property string $num_posts
 * @property string $num_topics
 * @property string $last_post_id
 * @property integer $poll
 * @property string $membergroup_id
 *
 * @property YBoardMembergroup $membergroup
 * @property YBoardLogTopic[] $yboardLogTopics
 * @property YBoardPost[] $yboardPosts
 * @property YBoardTopic[] $yboardTopics
 */
class YBoardForum extends \yii\db\ActiveRecord
{
    public $uid = 0; //current user ID querying this model. For use with getForums relations
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardForum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'type', 'public', 'locked', 'moderated', 'sort', 'num_posts', 'num_topics', 'last_post_id', 'poll', 'membergroup_id'], 'integer'],
            [['cat_id'], 'required', 'message'=>Yii::t('app','Parent category is required'), 'when' => function($model) { return $model->type==1; /*Parent required for forum not parent*/}],
            [['name'], 'required'],
            [['name', 'subtitle'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'Forum'),
            'cat_id' => YBoard::t('yboard', 'Category'),
            'name' => YBoard::t('yboard', 'Name'),
            'subtitle' => YBoard::t('yboard', 'Descriptions'),
            'type' => YBoard::t('yboard', 'Type'),
            'public' => YBoard::t('yboard', 'Public'),
            'locked' => YBoard::t('yboard', 'Locked'),
            'moderated' => YBoard::t('yboard', 'Moderated'),
            'sort' => YBoard::t('yboard', 'Sort'),
            'num_posts' => YBoard::t('yboard', 'Posts'),
            'num_topics' => YBoard::t('yboard', 'Topics'),
            'last_post_id' => YBoard::t('yboard', 'Last Post'),
            'poll' => YBoard::t('yboard', 'Poll'),
            'membergroup_id' => YBoard::t('yboard', 'Member Group'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(YBoardForum::className(), ['id' => 'cat_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForums()
    {
        return $this->hasMany(YBoardForum::className(), ['cat_id' => 'id'])
            ->andWhere('cat_id IS NOT NULL') 
            ->memberGroupScope($this->uid) 
            ->sortedScope(); 
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembergroup()
    {
        return $this->hasOne(YBoardMembergroup::className(), ['id' => 'membergroup_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogTopics()
    {
        return $this->hasMany(YBoardLogTopic::className(), ['forum_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(YBoardPost::className(), ['forum_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(YBoardTopic::className(), ['forum_id' => 'id']);
    }
    
    /**
     * @inheritdoc
     * @return YBoardForumQuery
     */
    public static function find()
    {
        return new YBoardForumQuery(get_called_class());
    }
    
    public function membergroup($membergroup=0) {
		return $this->find()
                    ->orWhere(['membergroup_id' => 0])
                    ->orWhere(['membergroup_id' => $membergroup])->all();
	}
    
    public static function getForumOptions($isGuest, $uid) {
		$return = []; 
		$category = YBoardForum::find()
                    ->where(['type'=>0])
                    ->orderBy('sort')
                    ->all();
                    
		foreach($category as $group) {
 			$forum = YBoardForum::find()
                    ->where(['type'=>1])
                    ->andWhere(['cat_id'=>$group->id])
                    ->orderBy('sort')
                    ->all();
                    
			foreach($forum as $option) {
				if($option->public || !$isGuest) {
					if($option->membergroup_id == 0) {
						$return[] = ['id'=>$option->id,'name'=>$option->name,'group'=>$group->name];
					} 
                    else if(!$isGuest) {
						$groupId = YBoardMember::findOne($uid)->group_id;
						if($option->membergroup_id == $groupId) {
							$return[] = ['id'=>$option->id,'name'=>$option->name,'group'=>$group->name];
						}
					}
				}
			}
		}
		return $return;
	}
    
    /**
	 * @return array relational rules.
	 */
	public function getLastPost()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return  $this->hasOne(YBoardPost::className(), ['id'=>'last_post_id']); 
	}
}

<?php

namespace app\modules\yboard\models;

use app\modules\yboard\YBoard;

use Yii;
use yii\helpers\Html;
use hosanna\profile\models\User;

/**
 * This is the model class for table "yboard_member".
 *
 * @property integer $id
 * @property string $location
 * @property string $personal_text
 * @property string $signature
 * @property integer $show_online
 * @property integer $contact_email
 * @property integer $contact_pm 
 * @property string $first_visit
 * @property string $last_visit
 * @property integer $ip
 * @property string $group_id
 * @property string $rank_id
 * @property integer $upvoted
 * @property string $blogger
 * @property string $facebook
 * @property string $skype
 * @property string $google
 * @property string $linkedin
 * @property string $skype
 * @property string $github
 * @property string $orkut
 * @property string $tumblr
 * @property string $twitter
 * @property string $website
 * @property string $wordpress
 * @property string $yahoo
 * @property string $youtube
 *
 * @property YBoardLogTopic[] $yboardLogTopics
 * @property YBoardTopic[] $topics
 * @property Users $profile
 * @property YBoardMembergroup $group
 * @property YBoardMessage[] $yboardMessages
 * @property YBoardPoll[] $yboardPolls
 * @property YBoardPost[] $yboardPosts
 * @property YBoardUpvoted[] $yboardUpvoteds
 * @property YBoardVote[] $yboardVotes
 */
class YBoardMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'YBoardMember';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'show_online', 'contact_email', 'contact_pm', 'group_id', 'rank_id'], 'integer'],
            [['first_visit', 'last_visit'], 'safe'],
            [['signature'], 'string'],
            // an inline validator for IP
            ['ip', function ($attribute, $params) {
                if (filter_var($this->$attribute, FILTER_VALIDATE_IP)) {
                    $this->addError($attribute, YBoard::t('yboard', 'Invalid IP Address'));
                }
            }],
            [['location', 'personal_text', 'blogger', 'facebook', 'skype', 'google', 'linkedin', 'metacafe', 'github', 'orkut', 'tumblr', 'twitter', 'website', 'wordpress', 'yahoo', 'youtube'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => YBoard::t('yboard', 'Member'),
            'location' => YBoard::t('yboard', 'Location'),
            'personal_text' => YBoard::t('yboard', 'Personal Text'),
            'signature' => YBoard::t('yboard', 'Signature'),
            'show_online' => YBoard::t('yboard', 'Show Online'),
            'contact_email' => YBoard::t('yboard', 'Allow Sending Email'),
            'contact_pm' => YBoard::t('yboard', 'Allow Sending PM'),
            'timezone' => YBoard::t('yboard', 'Timezone'),
            'first_visit' => YBoard::t('yboard', 'First Visit'),
            'last_visit' => YBoard::t('yboard', 'Last Visit'),
            'ip' => YBoard::t('yboard', 'IP Address'),
            'rank_id' => YBoard::t('yboard', 'Rank'),
            'group_id' => YBoard::t('yboard', 'Group'),
            'blogger' => YBoard::t('yboard', 'Blogger'),
            'facebook' => YBoard::t('yboard', 'Facebook'),
            'skype' => YBoard::t('yboard', 'Skype'),
            'google' => YBoard::t('yboard', 'Google'),
            'linkedin' => YBoard::t('yboard', 'Linkedin'),
            'metacafe' => YBoard::t('yboard', 'Metacafe'),
            'github' => YBoard::t('yboard', 'Github'),
            'orkut' => YBoard::t('yboard', 'Orkut'),
            'tumblr' => YBoard::t('yboard', 'Tumblr'),
            'twitter' => YBoard::t('yboard', 'Twitter'),
            'website' => YBoard::t('yboard', 'Website'),
            'wordpress' => YBoard::t('yboard', 'Wordpress'),
            'yahoo' => YBoard::t('yboard', 'Yahoo'),
            'youtube' => YBoard::t('yboard', 'Youtube'),
        ];
    }
    
    public function isBanned()
    {
        return YBoardBan::find()->where(['user_id'=>$this->id])->exists();
    }
    
    public function getRecentTopics()
    {
        $posts = $this->hasMany(YBoardTopic::className(), ['id' => 'topic_id'])->viaTable('YBoardLogTopic', ['member_id' => 'id'])
            ->limit(5)
            ->orderBy('id DESC')
            ->all();
        $items = [];
        foreach($posts as $post)
        {
            $items[] = Html::a($post->title, ['forum/topic', 'id'=>$post->id]);
        }
        return Html::ul($items, ['encode'=>false]);
    }
    
    /**
     * Total Topics started by user
     */
    public function getStartedTopics()
    {
        return $this->hasMany(YBoardTopic::className(), ['id' => 'topic_id'])->viaTable('YBoardLogTopic', ['member_id' => 'id'])->count();
    }

    /**
     * Total Topics started by user
     */
    public function getTotalReplies()
    {
        return $this->hasMany(YBoardPost::className(), ['user_id' => 'id']) 
            ->andWhere(['original_post'=>0])
            ->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogTopics()
    {
        return $this->hasMany(YBoardLogTopic::className(), ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(YBoardTopic::className(), ['id' => 'topic_id'])->viaTable('YBoardLogTopic', ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }
   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    { 
        return $this->hasOne(YBoardMembergroup::className(), ['id' => 'group_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRank()
    { 
        return $this->hasOne(YBoardRank::className(), ['id' => 'rank_id']);
    } 
        
    /**
     * @return YBoardMembergroup
     */
    public function getStatus()
    { 
        $isOnline = YBoardSession::find()->where(['user_id'=>$this->id])->count();
        
        if($this->show_online>0)
        {   
            if($isOnline>0)
            { 
                return YBoard::t('yboard', 'Online');
            }
            else
            {
                return YBoard::t('yboard', 'Offline');
            }
        }
        else
        {
            return YBoard::t('yboard', 'Hidden');
        }
    }
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(YBoardMessage::className(), ['sendto' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolls()
    {
        return $this->hasMany(YBoardPoll::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(YBoardPost::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpvotes()
    {
        return $this->hasMany(YBoardUpvoted::className(), ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(YBoardVote::className(), ['user_id' => 'id']);
    }
    
    /**
     * Number of Total Upvotes in All your posts
     */
    public function getAppreciations()
    {
        return YBoardUpvoted::find()->where(['author'=>$this->id])->count();
    }
    
    /**
     * @inheritdoc
     * @return YBoardMemberQuery
    */
    public static function find()
    {
        return new YBoardMemberQuery(get_called_class());
    }
    
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            //delete: Messages(to/from), Ban History, UpVotes, LogTopic
            YBoardMessage::deleteAll(['or', 'sendfrom'=>$this->id, 'sendto'=>$this->id]);
            // Post(Polls and Votes)
            YBoardVote::deleteAll(['user_id'=>$this->id]);
            YBoardUpvoted::deleteAll(['member_id'=>$this->id]);
            YBoardPoll::deleteAll(['user_id'=>$this->id]);
            
            YBoardPost::deleteAll(['user_id'=>$this->id]);
            
            //log topic
            YBoardLogTopic::deleteAll(['member_id'=>$this->id]);
            
            //ban
            YBoardBan::deleteAll(['user_id'=>$this->id]);   
                     
            return true;
        } else {
            return false;
        }
    }
}

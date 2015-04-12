<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\yboard\models\YBoardMember;

/**
 * YBoardMemberSearch represents the model behind the search form about `app\modules\yboard\models\YBoardMember`.
 */
class YBoardMemberSearch extends YBoardMember
{
    public function rules()
    {
        return [
            [['id', 'gender', 'show_online', 'contact_email', 'contact_pm', 'warning', 'posts', 'group_id', 'upvoted', 'moderator'], 'integer'],
            [['birthdate', 'location', 'personal_text', 'signature', 'avatar', 'timezone', 'first_visit', 'last_visit', 'blogger', 'facebook', 'github', 'google', 'linkedin', 'metacafe', 'skype', 'orkut', 'tumblr', 'twitter', 'website', 'wordpress', 'yahoo', 'youtube'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = YBoardMember::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id, 
            'birthdate' => $this->birthdate,
            'show_online' => $this->show_online,
            'contact_email' => $this->contact_email,
            'contact_pm' => $this->contact_pm,
            'first_visit' => $this->first_visit,
            'last_visit' => $this->last_visit,
            'warning' => $this->warning,
            'posts' => $this->posts,
            'group_id' => $this->group_id,
            'upvoted' => $this->upvoted,
            'moderator' => $this->moderator,
        ]);

        $query->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'personal_text', $this->personal_text])
            ->andFilterWhere(['like', 'signature', $this->signature])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'timezone', $this->timezone])
            ->andFilterWhere(['like', 'blogger', $this->blogger])
            ->andFilterWhere(['like', 'facebook', $this->facebook])
            ->andFilterWhere(['like', 'github', $this->github])
            ->andFilterWhere(['like', 'google', $this->google])
            ->andFilterWhere(['like', 'linkedin', $this->linkedin])
            ->andFilterWhere(['like', 'metacafe', $this->metacafe])
            ->andFilterWhere(['like', 'skype', $this->skype])
            ->andFilterWhere(['like', 'orkut', $this->orkut])
            ->andFilterWhere(['like', 'tumblr', $this->tumblr])
            ->andFilterWhere(['like', 'twitter', $this->twitter])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'wordpress', $this->wordpress])
            ->andFilterWhere(['like', 'yahoo', $this->yahoo])
            ->andFilterWhere(['like', 'youtube', $this->youtube]);

        return $dataProvider;
    }
}

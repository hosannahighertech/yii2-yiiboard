<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\yboard\models\YBoardForum;

/**
 * YBoardForumSearch represents the model behind the search form about `app\modules\yboard\models\YBoardForum`.
 */
class YBoardForumSearch extends YBoardForum
{
    public function rules()
    {
        return [
            [['id', 'cat_id', 'type', 'public', 'locked', 'moderated', 'sort', 'num_posts', 'num_topics', 'last_post_id', 'poll', 'membergroup_id'], 'integer'],
            [['name', 'subtitle'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = YBoardForum::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'cat_id' => $this->cat_id,
            'type' => $this->type,
            'public' => $this->public,
            'locked' => $this->locked,
            'moderated' => $this->moderated,
            'sort' => $this->sort,
            'num_posts' => $this->num_posts,
            'num_topics' => $this->num_topics,
            'last_post_id' => $this->last_post_id,
            'poll' => $this->poll,
            'membergroup_id' => $this->membergroup_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle]);

        return $dataProvider;
    }
}

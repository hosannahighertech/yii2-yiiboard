<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\yboard\models\YBoardSpider;

/**
 * YBoardSpiderSearch represents the model behind the search form about `app\modules\yboard\models\YBoardSpider`.
 */
class YBoardSpiderSearch extends YBoardSpider
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hits'], 'integer'],
            [['name', 'user_agent', 'last_visit'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = YBoardSpider::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'hits' => $this->hits,
            'last_visit' => $this->last_visit,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent]);

        return $dataProvider;
    }
}

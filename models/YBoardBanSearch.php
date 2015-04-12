<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\yboard\models\YBoardBan;

/**
 * YBoardBanSearch represents the model behind the search form about `app\modules\yboard\models\YBoardBan`.
 */
class YBoardBanSearch extends YBoardBan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'expires', 'banned_by'], 'integer'],
            [['ip', 'email', 'message'], 'safe'],
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
    public function search($params, $scope='active')
    {
        $query = YBoardBan::find();
        if($scope=='active')
            $query = $query->activeScope();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'expires' => $this->expires,
            'banned_by' => $this->banned_by,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}

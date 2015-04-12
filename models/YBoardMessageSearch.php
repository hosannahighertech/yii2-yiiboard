<?php

namespace app\modules\yboard\models;

use Yii;
use app\modules\yboard\YBoard;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\yboard\models\YBoardMessage;

/**
 * YBoardMessageSearch represents the model behind the search form about `app\modules\yboard\models\YBoardMessage`.
 */
class YBoardMessageSearch extends YBoardMessage
{
    public function rules()
    {
        return [
            [['id', 'sendfrom', 'sendto', 'read_indicator', 'type', 'inbox', 'outbox', 'post_id'], 'integer'],
            [['subject', 'content', 'create_time', 'ip'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = YBoardMessage::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'sendfrom' => $this->sendfrom,
            'sendto' => $this->sendto,
            'create_time' => $this->create_time,
            'read_indicator' => $this->read_indicator,
            'type' => $this->type,
            'inbox' => $this->inbox,
            'outbox' => $this->outbox,
            'post_id' => $this->post_id,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}

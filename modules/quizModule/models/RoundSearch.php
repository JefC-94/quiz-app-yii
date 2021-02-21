<?php

namespace app\modules\quizModule\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quizModule\models\Round;

/**
 * RoundSearch represents the model behind the search form of `app\models\Round`.
 */
class RoundSearch extends Round
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Round::find()->orderBy('order_index ASC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function all($params)
    {
        $query = Round::find();

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $query;
    }

    public function highestIndex()
    {
        $query = Round::find()->max('order_index');
        return $query;
    }
}
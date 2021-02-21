<?php

namespace app\modules\quizModule\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quizModule\models\QuizEvent;

/**
 * QuizEventSearch represents the model behind the search form of `app\modules\quizModule\models\QuizEvent`.
 */
class QuizEventSearch extends QuizEvent
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quiz_id', 'created_at'], 'integer'],
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
        $query = QuizEvent::find();

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
            'quiz_id' => $this->quiz_id,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }

    public function all($params){

        $query = QuizEvent::find();

        return $query;

    }
}

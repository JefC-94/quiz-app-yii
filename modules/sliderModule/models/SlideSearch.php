<?php

namespace app\modules\sliderModule\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sliderModule\models\Slide;

/**
 * SlideSearch represents the model behind the search form of `app\modules\sliderModule\models\Slide`.
 */
class SlideSearch extends Slide
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'slider_id', 'image_id', 'slide_index'], 'integer'],
            [['url', 'target'], 'safe'],
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
        $query = Slide::find();

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
            'slider_id' => $this->slider_id,
            'image_id' => $this->image_id,
            'slide_index' => $this->slide_index,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'target', $this->target]);

        return $dataProvider;
    }
}

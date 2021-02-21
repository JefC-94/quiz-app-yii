<?php

namespace app\modules\quizModule\models;

use Yii;
use app\modules\quizModule\models\Question;
use app\models\Team;

/**
 * This is the model class for table "record".
 *
 * @property int $id
 * @property int $team_id
 * @property int $round_id
 * @property int $order_index
 * @property string|null $answer
 * @property int|null $correct
 *
 * @property Round $round
 */
class Record extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'round_id', 'order_index'], 'required'],
            [['team_id', 'round_id', 'order_index'], 'integer'],
            [['answer'], 'string', 'max' => 50],
            [['round_id'], 'exist', 'skipOnError' => true, 'targetClass' => Round::className(), 'targetAttribute' => ['round_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_id' => 'Team ID',
            'round_id' => 'Round ID',
            'order_index' => 'Order Index',
            'answer' => 'Answer',
            'correct' => 'Correct',
        ];
    }

    /**
     * Gets query for [[Round]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRound()
    {
        return $this->hasOne(Round::className(), ['id' => 'round_id']);
    }

    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['round_id' => 'round_id', 'order_index' => 'order_index']);
    }

    public function getTeam()
    {
        return $this->hasOne(Users::className(), ['id' => 'team_id']);
    }

}

<?php

namespace app\modules\quizModule\models;

use Yii;
use app\models\Team;

/**
 * This is the model class for table "quiz_event".
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $created_at
 *
 * @property Quiz $quiz
 * @property Team[] $teams
 */
class QuizEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'created_at'], 'required'],
            [['quiz_id', 'created_at'], 'integer'],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quiz_id' => 'Quiz ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Quiz]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::className(), ['id' => 'quiz_id']);
    }

    /**
     * Gets query for [[Teams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Team::className(), ['quiz_event_id' => 'id']);
    }

    public function getTeamsPaged($pages)
    {
        $query = $this->hasMany(Team::className(), ['quiz_event_id' => 'id'])->orderBy('score DESC');
        return $query->offset($pages->offset)->limit($pages->limit);
    }

}

<?php

namespace app\modules\quizModule\models;

use Yii;

/**
 * This is the model class for table "quiz".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property QuizEvent[] $quizEvents
 */
class Quiz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'description', 'created_by', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
            [['created_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[QuizEvents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizEvents()
    {
        return $this->hasMany(QuizEvent::className(), ['quiz_id' => 'id']);
    }
}

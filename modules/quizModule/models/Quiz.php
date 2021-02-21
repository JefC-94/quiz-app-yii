<?php

namespace app\modules\quizModule\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use app\models\Profile;
use app\modules\quizModule\models\Round;
use app\modules\quizModule\models\RoundSearch;

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

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
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


    public function getCreatedBy()
    {
        return $this->hasOne(Profile::className(), ['id' => 'created_by']);
    }

    public function getRounds()
    {
        return $this->hasMany(Round::className(), ['quiz_id' => 'id']);
    }

    public function getRoundsPaged($pages)
    {
        $query = $this->hasMany(Round::className(), ['quiz_id' => 'id'])->orderBy('order_index ASC');
        return $query->offset($pages->offset)->limit($pages->limit);
    }

}

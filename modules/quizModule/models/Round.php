<?php

namespace app\modules\quizModule\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\web\UploadedFile;
use app\modules\quizModule\models\Quiz;
use app\modules\quizModule\models\QuizSearch;

/**
 * This is the model class for table "round".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $order_index
 *
 * @property Question[] $questions
 */
class Round extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'round';
    }

    public function behaviors()
    {
        return [
            //TimestampBehavior::class,
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
            [['name', 'order_index'], 'required'],
            [['order_index'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'order_index' => 'Order Index',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRound($slug)
    {
        return self::findOne(['slug' => $slug]);
    }


    public function getQuiz()
    {
        return $this->hasOne(Quiz::className(), ['id' => 'quiz_id']);
    }




    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['round_id' => 'id'])->orderBy('order_index ASC');
    }

    public function getQuestionsPaged($pages)
    {
        $query = $this->hasMany(Question::className(), ['round_id' => 'id'])->orderBy('order_index ASC');
        return $query->offset($pages->offset)->limit($pages->limit);
    }


    //FIND Question by index
    public function getQuestionByIndex($id, $order_index)
    {
        $query = Question::find()
            ->where(['round_id' => $id])
            ->andWhere(['order_index' => $order_index])
            ->one();
        return $query;

        return $this->hasMany(Question::className(), ['round_id' => 'id'], ['order_index' => $order_index]);
    }

    /**
     * 
     * Order queries, general so it can be used in any model!
     * 
    */
    public function getItemsByIndex($order_index, $quiz_id)
    {
        return self::find()->where(['order_index' => $order_index, 'quiz_id' => $quiz_id])->one();
    }

    public function getItemsBelowIndex($order_index, $quiz_id)
    {
        return self::find()->where(['<', 'order_index', $order_index])->andWhere(['quiz_id' => $quiz_id])->all();
    }

    public function getItemsAboveIndex($order_index, $quiz_id)
    {
        return self::find()->where(['>', 'order_index', $order_index])->andWhere(['quiz_id' => $quiz_id])->all();
    }



}

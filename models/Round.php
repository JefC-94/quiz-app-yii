<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\web\UploadedFile;

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

    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['round_id' => 'id'])->orderBy('order_index ASC');
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
    public function getItemsByIndex($order_index)
    {
        return self::find()->where(['order_index' => $order_index])->one();
    }

    public function getItemsBelowIndex($order_index)
    {
        return self::find()->where(['<', 'order_index', $order_index])->all();
    }

    public function getItemsAboveIndex($order_index)
    {
        return self::find()->where(['>', 'order_index', $order_index])->all();
    }



}

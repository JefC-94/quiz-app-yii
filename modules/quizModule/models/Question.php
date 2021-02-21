<?php

namespace app\modules\quizModule\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property int $id
 * @property int $round_id
 * @property int $order_index
 * @property string $inquiry
 * @property string $image
 * @property string $answer
 * @property string|null $wrong_options
 *
 * @property Round $round
 * @property Record[] $records
 */
class Question extends \yii\db\ActiveRecord
{

    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['round_id', 'order_index', 'inquiry', 'answer'], 'required'],
            [['round_id', 'order_index'], 'integer'],
            [['inquiry'], 'string'],
            [['image'], 'string', 'max' => 255],
            [['answer', 'wrong_options'], 'string', 'max' => 555],
            [['round_id'], 'exist', 'skipOnError' => true, 'targetClass' => Round::className(), 'targetAttribute' => ['round_id' => 'id']],

            [['imageFile'], 'file', 'skipOnEmpty' => false, 'on' => 'create', 'extensions' => 'jpg,png,gif'],

            [['imageFile'], 'file', 'skipOnEmpty' => true, 'on' => 'update', 'extensions' => 'jpg,png,gif'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'round_id' => 'Round ID',
            'order_index' => 'Order Index',
            'inquiry' => 'Inquiry',
            'image' => 'Image',
            'answer' => 'Answer',
            'wrong_options' => 'Wrong Options (comma separated values e.g; John, Ringo, Paul)',
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

    /**
     * Gets query for [[Records]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecords()
    {
        return $this->hasMany(Record::className(), ['question_id' => 'id']);
    }


    /**
     * 
     * Order queries -> linked to each round!!
     * 
    */
    public function getItemsByIndex($order_index, $round_id)
    {
        return self::find()->andWhere(['order_index' => $order_index, 'round_id' => $round_id])->one();
    }

    public function getItemsBelowIndex($order_index, $round_id)
    {
        return self::find()->andWhere(['<', 'order_index', $order_index])->andWhere(['round_id' => $round_id])->all();
    }

    public function getItemsAboveIndex($order_index, $round_id)
    {
        return self::find()->andWhere(['>', 'order_index', $order_index])->andWhere(['round_id' => $round_id])->all();
    }



}

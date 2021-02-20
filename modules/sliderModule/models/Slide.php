<?php

namespace app\modules\sliderModule\models;

use Yii;
use app\modules\sliderModule\models\Slider;
use app\modules\sliderModule\models\SliderSearch;
use app\modules\sliderModule\models\Sl_Image;


/**
 * This is the model class for table "sl_slide".
 *
 * @property int $id
 * @property int|null $slider_id
 * @property int|null $image_id
 * @property int $slide_index
 * @property string $url
 * @property string $target
 *
 * @property Sl_Image $image
 * @property Slider $slider
 */
class Slide extends \yii\db\ActiveRecord
{

    public $imageFile;
    public $min_width;
    public $min_height;
    public $slug;
    public $page;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sl_slide';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slider_id', 'image_id'], 'integer'],
            [['url', 'target', 'page'], 'string', 'on' => 'create', 'on' => 'update'],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sl_Image::className(), 'targetAttribute' => ['image_id' => 'id']],
            [['slider_id'], 'exist', 'skipOnError' => true, 'targetClass' => Slider::className(), 'targetAttribute' => ['slider_id' => 'id']],
        
            [['imageFile'], 'image', 'minWidth' => $this->min_width, 'minHeight' => $this->min_height],
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
            'slider_id' => 'Slider ID',
            'image_id' => 'Image ID',
            'slide_index' => 'Slide Index',
            'page' => 'Page',
            'url' => 'Url',
            'target' => 'Open in new tab?',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getSlide($id)
    {
        return self::findOne($id);
    }

    public function getSlideByIndex($slider_id, $slide_index)
    {

        $query = self::find()
            ->andWhere(['slider_id' => $slider_id])
            ->andWhere(['slide_index' => $slide_index])
            ->one();

        return $query;
        
    }

    public function getSlidesBelowIndex($slider_id, $slide_index)
    {

        $ids = array();

        $query = self::find()
            ->andWhere(['slider_id' => $slider_id])
            ->andWhere(['<', 'slide_index', $slide_index])
            ->all();

        return $query;

        foreach($query as $mini){
            foreach ($mini as $item){
                array_push($ids, $item);
            }
        }

        return $ids;

    }

    public function getSlidesAboveIndex($slider_id, $slide_index)
    {

        $ids = array();

        $query = self::find()
            ->andWhere(['slider_id' => $slider_id])
            ->andWhere(['>', 'slide_index', $slide_index])
            ->all();

        return $query;

        foreach($query as $mini){
            foreach ($mini as $item){
                array_push($ids, $item);
            }
        }

        return $ids;

    }

    /**
     * Gets query for [[Image]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Sl_Image::className(), ['id' => 'image_id']);
    }

    /**
     * Gets query for [[Slider]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSlider()
    {
        return $this->hasOne(Slider::className(), ['id' => 'slider_id']);
    }

}

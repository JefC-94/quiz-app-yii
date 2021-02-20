<?php

namespace app\modules\sliderModule\models;

use Yii;
use app\models\User;
use app\modules\sliderModule\models\Slide;
use app\modules\sliderModule\models\Sl_Image;
use app\models\Article;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "sl_slider".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $slug
 * @property int $width
 * @property int $height
 * @property float $aspect_ratio
 * @property int $gap
 * @property int $lightbox
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Slide[] $slSlides
 */
class Slider extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sl_slider';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
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
            [['width', 'height', 'name'], 'required'],
            [['width', 'height', 'gap', 'lightbox', 'created_at', 'updated_at'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 55],
            [['name'], 'unique'],       
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
            'width' => 'Width (px)',
            'height' => 'Height (px)',
            'gap' => 'Gap (px) : distance between images if multiple images are appearing on one slide',
            'lightbox' => 'Enlarge images when clicked (any possible links will not work)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

   /**
     * Get all sliders
     * 
     */
    public function getAllSliders()
    {
        return Slider::find()->all();
    }


   /**
     * {@inheritdoc}
     */
    public function getSlider($slug)
    {
        return self::findOne(['slug' => $slug]);
    }


    /**
     * Gets query for [[SlSlides]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSlides()
    {
        return $this->hasMany(Slide::className(), ['slider_id' => 'id'])->orderBy("slide_index");
    }


    /**
     * Get all image paths. This function will be unneccesary -> use the above function instead to work with objects!!!
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSliderImages()
    {
        
        $id = $this->id;
        $image_ids = array();
        $images = array();

        $slide_query = Slide::find()
            ->select('image_id')
            ->where(['slider_id' => $id])
            ->asArray()
            ->all();

        foreach($slide_query as $mini){
            foreach ($mini as $item){
                array_push($image_ids, $item);
            }
        }

        $image_query = Sl_Image::find()
            ->select('imagepath')
            ->where(['in', 'id', $image_ids])
            ->asArray()
            ->all();

        foreach($image_query as $mini){
            foreach ($mini as $item){
                array_push($images, $item);
            }
        }

        return $images;
    
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getWidth()
    {
        return $this->width + "px";
    }

    public function getHeight()
    {
        return $this->height + "px";
    }

    public function getMinWidth()
    {
        $min_width = number_format($this->width * .70);
        return $min_width;
    }

    public function getMinHeight()
    {
        $min_height = number_format($this->height * .70);
        return $min_height;
    }

    public function getCountSlides()
    {
        return $this->hasMany(Slide::className(), ['slider_id' => 'id'])->count();
    }

}

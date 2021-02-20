<?php

namespace app\modules\sliderModule\models;

use Yii;
use app\modules\sliderModule\models\Slider;
use app\modules\sliderModule\models\SliderSearch;
use app\modules\sliderModule\models\Slide;

/**
 * This is the model class for table "sl_image".
 *
 * @property int $id
 * @property string|null $imagepath
 *
 * @property SlSlide[] $slSlides
 */
class Sl_Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sl_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imagepath'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'imagepath' => 'Imagepath',
        ];
    }


    public function getSl_Image($id)
    {
        return self::findOne($id);
    }


    /**
     * Gets query for [[SlSlides]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSlSlides()
    {
        return $this->hasMany(Slide::className(), ['image_id' => 'id']);
    }
}

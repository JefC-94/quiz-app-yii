<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use app\models\User;
use app\modules\postModule\models\Post;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string $name
 * @property string $slug
 * @property string|null $image
 * @property string|null $bio
 * @property string|null $company
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{

    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['bio'], 'string'],
            [['firstname', 'lastname', 'company', 'bio'], 'required'],
            [['firstname', 'lastname', 'company'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],

            [['firstname', 'lastname'], 'unique', 'targetAttribute' => ['firstname', 'lastname'], 'message' => 'A profile for this author has already been created'],

            [['imageFile'], 'file', 'skipOnEmpty' => false, 'on' => 'create', 'extensions' => 'jpg,png,gif'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'on' => 'update', 'extensions' => 'jpg,png,gif'],

        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'name' => 'Name',
            'slug' => 'Slug',
            'imageFile' => 'Selecteer een afbeelding',
            'bio' => 'Bio',
            'company' => 'Company',
        ];
    }

    /**
     * {@inheritdoc}
     * Get all Profiles with their id function.
     */
    public function getProfiles()
    {
        return Profile::find()->all();
    } 


    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    protected function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


}

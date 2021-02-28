<?php

namespace app\modules\quizModule\models\team;

use Yii;
use yii\db\ActiveRecord;
use app\models\Profile;
use app\modules\quizModule\models\Record;
use app\modules\quizModule\models\QuizEvent;
use kartik\password\StrengthValidator;

/**
* This is the model class for table "teams".
*
* @property int $id
* @property string $varchar_id
* @property string $username
* @property string $score
* @property int $quiz_event_id
* @property string $auth_key
* @property string $access_token
*/

class Team extends ActiveRecord implements \yii\web\IdentityInterface
{

    public $roles;
    public $selectedRoles = array();

    public static function tableName(){
        return 'team';
    }

    /**
    * {@inheritdoc}
    */

    public function rules()
    {
        return [

            //ALL
            [['username'], 'string', 'min' => 4, 'max' => 55],

            //UNIQUE
            [['username'], 'unique', 'message' => 'This username is already taken'],

            //CREATE & UPDATE
            [['username', 'quiz_event_id', 'auth_key', 'access_token'], 'required', 'on' => 'create'],
            ['selectedRoles', 'required', 'message' => 'Selecteer minstens 1 categorie', 'on' => 'create'],
            ['selectedRoles', 'required', 'message' => 'Selecteer minstens 1 categorie', 'on' => 'update'],
            
            
        ];
    }
    /**
     * {@inheritdoc}
     */

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'username' => 'Username',
            'score' => 'Score',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne('varchar_id');
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()->where(['access_token' => $token])->one();
    }

    /**
     * Finds team by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->varchar_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function getQuizEvent()
    {
        
        return $this->hasOne(QuizEvent::className(), ['id' => 'quiz_event_id']);
    }

    public function getRecord()
    {
        return $this->hasMany(Record::className(), ['team_id' => 'id']);
    }

    public function getCompletedRounds($id)
    {
        $rounds = Record::find()->select('round.order_index')->distinct()->where(['team_id' => $id])->innerJoinWith('round');  
        
        //return $rounds->createCommand()->getRawSql();
        
        return $rounds->all();

    }

}

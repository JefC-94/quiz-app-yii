<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\Profile;
use app\models\Record;
use kartik\password\StrengthValidator;

/**
* This is the model class for table "teams".
*
* @property int $id
* @property string $username
* @property string $score
* @property string $email
* @property string $password
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
            [['username', 'auth_key', 'access_token', 'email'], 'required', 'on' => 'create'],
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
        return self::findOne($id);
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
        return $this->id;
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

    public function isAdmin()
    {
        $query = $this->hasMany(Role::className(), ['id' => 'role_id'])
        ->viaTable('user_role', ['user_id' => 'id'])
        ->where(['rolename' => 'admin'])
        ->all();

        if(empty($query)){
            return false;
        } else {
            return true;
        }
    }

    public function isEditor()
    {
        $query = $this->hasMany(Role::className(), ['id' => 'role_id'])
        ->viaTable('user_role', ['user_id' => 'id'])
        ->where(['rolename' => 'editor'])
        ->all();

        if(empty($query)){
            return false;
        } else {
            return true;
        }
    }

    public function isAuthor()
    {
        $query = $this->hasMany(Role::className(), ['id' => 'role_id'])
        ->viaTable('user_role', ['user_id' => 'id'])
        ->where(['rolename' => 'author'])
        ->all();

        if(empty($query)){
            return false;
        } else {
            return true;
        }
    }

    public function isMember()
    {
        $query = $this->hasMany(Role::className(), ['id' => 'role_id'])
        ->viaTable('user_role', ['user_id' => 'id'])
        ->where(['rolename' => 'member'])
        ->all();

        if(empty($query)){
            return false;
        } else {
            return true;
        }
    }

    public function getUserRole()
    {
        return $this->hasMany(UserRole::className(), ['user_id' => 'id']);
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

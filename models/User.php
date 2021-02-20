<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\Profile;
use app\models\Record;
use kartik\password\StrengthValidator;

/**
* This is the model class for table "user".
*
* @property int $id
* @property string $username
* @property string $score
* @property string $email
* @property string $password
* @property string $auth_key
* @property string $access_token
*/

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public $roles;
    public $selectedRoles = array();
    public $password_repeat;
    public $old_password;
    public $new_password;

    public static function tableName(){
        return 'users';
    }

    /**
    * {@inheritdoc}
    */

    public function rules()
    {
        return [

            //ALL
            [['username'], 'string', 'min' => 4, 'max' => 55],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            [['password', 'auth_key', 'access_token'], 'string', 'max' => 255],
            [['password'], StrengthValidator::className(), 'preset' => 'normal', 'userAttribute' => 'username'],
            
            //UNIQUE
            [['username'], 'unique', 'message' => 'This username is already taken'],
            [['email'], 'unique', 'message' => 'This email-adres is already taken'], 

            //CREATE & UPDATE
            [['username', 'password', 'auth_key', 'access_token', 'email'], 'required', 'on' => 'create'],
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
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Repeat',
            'auth_key' => 'Auth Key',
            'old_password' => 'Old Password',
            'new_password' => 'New Password',
            'access_token' => 'Access Token',
        ];
    }

    public function relations()
    {
        return array(
            'profile' => array(self::HAS_MANY, 'Profile', 'user_id'),
        );
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
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
    * Finds user by email
    *
    * @param string $email
    * @return static|null
    */
    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
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

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
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

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
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

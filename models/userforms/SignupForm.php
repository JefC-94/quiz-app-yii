<?php

namespace app\models\userforms;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;
use app\models\User;
use app\models\UserRole;
use app\models\Profile;
use yii\web\ForbiddenHttpException;
use kartik\password\StrengthValidator;

/**
 * SignupForm is the model behind the signup form.
 *
 * @property User|null $user This property is read-only.
 * @property Profile|null $profile This property is read-only.
 */

class SignupForm extends Model
{

    public $user;
    public $username;
    public $email;
    public $password;
    public $password_repeat;
 
    public function rules()
    {

        return [
            //Validation before submit
            [['username', 'password', 'password_repeat', 'email'], 'required'],
            [['password'], StrengthValidator::className(), 'preset' => 'normal', 'userAttribute' => 'username'],

            [['username', 'password', 'password_repeat'], 'string', 'min' => 4, 'max' => 16],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['email', 'email'],

            //Custom checks for db -> user unique validation (won't work on user model)
            ['username', 'uniqueUsername'],
            ['email', 'uniqueEmail'],
        ];

    }

    public function uniqueUsername($attribute, $params)
    {
        $username = User::find()->where(["username" => $this->username])->count();
        
        if($username){
            $this->addError($attribute, 'Username already used!');
        }
    }

    public function uniqueEmail($attribute, $params)
    {
        $email = User::find()->where(["email" => $this->email])->count();

        if($email){
            $this->addError($attribute, 'Email already taken!');
        }
    }



    /**
     * Signup function bestaat enkel nog indien ik zelf als admin een account moet aanmaken (en dit niet mogelijk is met user create)
     * Throws an Forbidden Error
     */
    public function signup()
    {

        $user = new User();
        $user->username = strip_tags($this->username);
        $user->email = strip_tags($this->email);
        $user->password = \Yii::$app->security->generatePasswordHash($this->password);
        $user->access_token = \Yii::$app->security->generateRandomString();
        $user->auth_key = \Yii::$app->security->generateRandomString();

        $user->selectedRoles = ["member"];

        if($this->validate()){

            if($user->save()){
                
                $user_role = new UserRole;
                $user_role->user_id = $user->id;
                $user_role->role_id = 4;
                if( !$user_role->save()) print_r($user_role->errors);
                
                return Yii::$app->user->login($user);
            } else {
                return false;
            }
        } else {
            return false;   
        }

    \Yii::error( "User was not saved" . VarDumper::dumpAsString($user->errors));
    return false;

    }
}

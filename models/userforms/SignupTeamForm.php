<?php

namespace app\models\userforms;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;
use app\models\Team;
use yii\web\ForbiddenHttpException;
use kartik\password\StrengthValidator;

/**
 * SignupForm is the model behind the signup form.
 *
 * @property Team|null $user This property is read-only.
 */

class SignupTeamForm extends Model
{

    public $team;
    public $username;
    public $quiz_event_id;
 
    public function rules()
    {

        return [
            //Validation before submit
            [['username'], 'required'],

            //Custom checks for db -> user unique validation (won't work on user model)
            ['username', 'uniqueUsername'],
        ];

    }

    public function uniqueUsername($attribute, $params)
    {
        $username = Team::find()->where(["username" => $this->username])->count();
        
        if($username){
            $this->addError($attribute, 'Deze teamnaam bestaat al!');
        }
    }

    /**
     * Signup function bestaat enkel nog indien ik zelf als admin een account moet aanmaken (en dit niet mogelijk is met user create)
     * Throws an Forbidden Error
     */
    public function signup()
    {

        $team = new Team();
        $team->username = strip_tags($this->username);
        $team->score = 0;
        $team->access_token = \Yii::$app->security->generateRandomString();
        $team->auth_key = \Yii::$app->security->generateRandomString();
        $team->quiz_event_id = 1;

        if($this->validate()){

            if($team->save()){
                return Yii::$app->team->login($team);
            } else {
                return false;
            }
        } else {
            return false;   
        }

    \Yii::error( "User was not saved" . VarDumper::dumpAsString($team->errors));
    return false;

    }
}

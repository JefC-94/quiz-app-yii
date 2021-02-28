<?php

namespace app\modules\quizModule\models\team;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;
use app\modules\quizModule\models\team\Team;
use yii\web\ForbiddenHttpException;
use kartik\password\StrengthValidator;

/**
 * SignupTeamForm is the model behind the signup form.
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
    public function signup($id)
    {

        $team = new Team();
        $team->varchar_id = "t" . $this->generateRandomString();
        //TODO: nadenken of dit auto_increment kan zijn... is niet per se nodig maar mag wel!
        $team->username = strip_tags($this->username);
        $team->score = 0;
        $team->access_token = \Yii::$app->security->generateRandomString();
        $team->auth_key = \Yii::$app->security->generateRandomString();
        $team->quiz_event_id = $id;

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


    public function generateRandomString($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

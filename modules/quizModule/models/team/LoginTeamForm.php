<?php

namespace app\modules\quizModule\models\team;

use Yii;
use yii\base\Model;
use app\modules\quizModule\models\team\Team;

/**
 * LoginTeamForm is the model behind the login team form.
 *
 * @property Team|null $user This property is read-only.
 *
 */
class LoginTeamForm extends Model
{
    public $username;
    public $rememberMe = true;
    private $_team = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username required
            [['username'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            
            // validate username to prevent XSS
            ['username', 'validateUsername'],
        ];
    }

	public function validateUsername($attribute, $params)
    {
        $this->username = strip_tags($this->username);

        if (!$this->hasErrors()) {
            $user = $this->getTeam();

            if (!$user) {
                $this->addError($attribute, 'Incorrect username.');
            }
        }
    }


    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->team->login($this->getTeam(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds team by [[username]]
     *
     * @return Team|null
     */
    public function getTeam()
    {
        if ($this->_team === false) {
            $this->_team = Team::findByUsername($this->username);
            //ALSO CHECK DAT HET JUISTE QUIZ IS?
        }

        return $this->_team;
    }
}

<?php

namespace app\models\userforms;

use Yii;
use app\models\User;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use kartik\password\StrengthValidator;

/**
 * EditForm is the model behind the Edit form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class EditForm extends Model
{
    public $user;
    public $username;
    public $email;
    public $password;
    public $old_password;
    public $new_password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'email'], 'required'],
            ['email', 'email'],
            
            ['old_password', 'required'],

            // password is validated by validatePassword()
            ['old_password', 'validatePassword'],

            // new and old password can't be the same!
            ['new_password', 'compare', 'compareAttribute' => 'old_password', 'operator' => '!=', 'message' => 'new password cannot be old password'],
            [['new_password'], StrengthValidator::className(), 'preset' => 'normal', 'userAttribute' => 'username'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if (!$this->user->validatePassword($this->old_password)) {
                $this->addError($attribute, 'Incorrect password.');
            }
        }
    }

    /**
     * Edits in a user and gives a new password if necessary
     * @return bool whether the user is edited succesfully
     */
    public function edit($id)
    { 

        $this->old_password = $_POST['EditForm']['old_password'];
        if(!empty($_POST['EditForm']['new_password'])){
            $this->new_password = $_POST['EditForm']['new_password'];
        }
        
        $this->user->username = strip_tags($_POST['EditForm']['username']);
        $this->user->email = strip_tags($_POST['EditForm']['email']);

        if(!empty($this->new_password)){
            $this->user->password = \Yii::$app->security->generatePasswordHash($_POST['EditForm']['new_password']);
        }

        if($this->user->save()){
            return true;
        }
        
        return false;
    }


}


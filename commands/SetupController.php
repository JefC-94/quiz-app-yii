<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\modules\keywordModule\models\Keyword;
use app\models\User;
use app\models\UserRole;
use app\models\Role;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SetupController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionCreateAdmin($username, $email)
    {

        $password = $this->prompt(
            'Enter password',
            [
                'required' => true,
                'validator' => function ($input, &$error){
                    if(strlen($input) < 8){
                        $error = "The password must be at least 8 characters";
                        return false;
                    }
                    if(!preg_match('/[A-Z]/', $input)){
                        $error = "The password must contain at least one uppercase character";
                        return false;
                    }
                    if(!preg_match('/[0-8]/', $input)){
                        $error = "The password must contain at least one digit";
                        return false;
                    }
                    return true;
                },
            ]
            );

        $user = new User();

        $user->username = $username;
        $user->email = $email;
        $user->password = \Yii::$app->security->generatePasswordHash($password);
        $user->access_token = \Yii::$app->security->generateRandomString();
        $user->auth_key = \Yii::$app->security->generateRandomString();
        
        if($user->save()){

            $user_role = new UserRole();

            $user_role->user_id = $user->id;
            $user_role->role_id = 1;

            if($user_role->save()){
                echo "User created with name " . $username . " and email " . $email;
                return ExitCode::OK;
            }
        }
    }

}

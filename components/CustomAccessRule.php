<?php

namespace app\components;

// THIS IS NOT WORKING CURRENTLY!!!!!!!
class CustomAccessRule extends \yii\filters\AccessRule

{

    protected function matchRole($user)

    {
        $sessionUser = Yii::$app->user->identity;
        
        $items = empty($this->roles) ? [] : $this->roles;

        if (!empty($this->permissions)) {
            $items = array_merge($items, $this->permissions);
        }

        if (empty($items)) {
            return true;
        }

        if ($user === false) {
            throw new InvalidConfigException('The user application component must be available to specify roles in AccessRule.');
        }

        foreach ($items as $item) {
            if ($item === '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif ($item === '@') {
                if (!$user->getIsGuest()) {
                    return true;
                }
            //first check if user is logged in again, then check if user is admin, editor or author
            }
            if($sessionUser){
                if ($item === 'admin') {
                    if ($sessionUser->isAdmin()){
                        return true;
                    }
                } elseif ($item === 'editor') {
                    if ($sessionUser->isEditor()){
                        return true;
                    }
                } elseif ($item === 'author') {
                    if ($sessionUser->isAuthor()){
                        return true;
                    }
                } elseif ($item === 'member') {
                    if ($sessionUser->isMember()){
                        return true;
                    }
                } else {
                    if (!isset($roleParams)) {
                        $roleParams = !is_array($this->roleParams) && is_callable($this->roleParams) ? call_user_func($this->roleParams, $this) : $this->roleParams;
                    }
                    if ($user->can($item, $roleParams)) {
                        return true;
                    }
                }
            }
        }

        return false;

    }

}
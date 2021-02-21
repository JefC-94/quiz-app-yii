<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\controllers\UserController;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$sessionUser = Yii::$app->user->identity;
?>
<div class="container inner">

    <div class="userzone">
        <?php if($sessionUser->isAdmin()){ ?>
            <?php if(!$model->isMember()){ ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'userzonebtn']) ?>
            <?php } ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'userzonebtn delete',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php } if(Yii::$app->user->identity->id == $model->id){ ?>
        <?= Html::a('Edit self', ['edit', 'id' => $model->id], ['class' => 'userzonebtn']) ?>
        <?php } ?>
    </div>

    <div class="back-to-index">
        <?php 
        if($model->isMember()){
            echo Html::a('Back to teams', ['user/members']);
        } else {
            echo Html::a('Back to users', ['user/index']);
        }
        ?>
    </div>

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <div class="admin-table-wrapper">
        <table class="admin compact">
            <tr>
                <td>Username</td><td><?= $model->username ?></td>
            </tr> 
            <tr>
                <td>Roles</td><td><?php foreach($model->userRole as $user_role){echo $user_role->role->rolename . " ";} ?></td>
            </tr>
        </table>
    </div>

    <div style="height:100px;"></div>    

</div>
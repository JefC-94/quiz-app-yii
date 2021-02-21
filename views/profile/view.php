<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = $model->firstname . " " . $model->lastname;
$this->params['breadcrumbs'][] = ['label' => 'Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$sessionUser = Yii::$app->user->identity;
?>

<div class="container inner">

    <div class="userzone">
        <?php if(!Yii::$app->user->isGuest){ ?>
        <?php if($sessionUser->id == $model->user_id){ ?>
            <?= Html::a('Update', ['update', 'slug' => $model->slug], ['class' => 'userzonebtn']) ?>
        <?php }} ?>
    </div>

    <div class="back-to-index">
        <?php 
            echo Html::a('Back to blog', ['/blog']);
            if(!Yii::$app->user->isGuest){
                echo Html::a('Back to profiles', ['profile/index']);
            }
        ?>
    </div>

    <div class="profile-wrap">
        
        <div class="profile-content">
            <h1 class="profile-title"><?= Html::encode($this->title) ?></h1>

            <p class="bio"><?= Html::encode($model->bio) ?></p>

            <p><?= Html::encode($model->company) ?></p>
        </div>
        <div class="profile-image">
            <?= Html::img('@web/'.$model->image); ?>
        </div>
    </div>

</div><!-- end of container inner -->

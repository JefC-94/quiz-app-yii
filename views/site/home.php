<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Home';

?>

<div class="fullwh lobby-page">

    <h1>QuarantaineQuiz</h1>
    <h2>by MaBoiJohn & Jeffrey C Esq.</h2>

    <?php if(Yii::$app->user->isGuest){
        echo Html::a('Signup for quiz', ['/signupteam'], ['class' => 'lobby', 'id' => 'signup-button']);
    } ?>

</div>
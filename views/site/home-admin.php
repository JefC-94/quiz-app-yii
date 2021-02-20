<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Home';

?>

<div class="fullwh lobby-page">
    <h1>QuarantaineQuiz</h1>
    
    <h2>by MaBoiJohn & Jeffrey C Esq.</h2>

    <?php echo Html::a('Start quiz', ['round/startquiz'], ['class' => 'lobby', 'id' => 'start-button']) ?>
        
</div>
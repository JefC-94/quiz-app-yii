<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\quizModule\models\QuizEvent */

$this->title = 'Create Quiz Event';
$this->params['breadcrumbs'][] = ['label' => 'Quiz Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quiz-event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

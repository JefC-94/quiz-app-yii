<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\quizModule\models\QuizEvent */

$this->title = 'Update Quiz Event: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Quiz Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="quiz-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

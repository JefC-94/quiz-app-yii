<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\quizModule\models\QuizEvent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="quiz-event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'quiz_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

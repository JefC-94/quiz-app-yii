<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\QuestionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'round_id') ?>

    <?= $form->field($model, 'order_index') ?>

    <?= $form->field($model, 'inquiry') ?>

    <?= $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'answer') ?>

    <?php // echo $form->field($model, 'wrong_1') ?>

    <?php // echo $form->field($model, 'wrong_2') ?>

    <?php // echo $form->field($model, 'wrong_3') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

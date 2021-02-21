<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TeamSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-searchfields">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'quiz_event_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'usertablebtn']) ?>
        <?= Html::a('Reset', ['index'], ['class' => 'usertablebtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

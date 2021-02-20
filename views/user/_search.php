<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SearchUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-searchfields">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'username') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'usertablebtn']) ?>
        <?= Html::a('Reset', ['index'], ['class' => 'usertablebtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

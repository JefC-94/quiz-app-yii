<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Round */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group userzone">
        <?= Html::submitButton('Save', ['class' => 'userzonebtn save']) ?>
        <?php echo Html::a('Cancel', ['round/index'], ['class'=>'userzonebtn back'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

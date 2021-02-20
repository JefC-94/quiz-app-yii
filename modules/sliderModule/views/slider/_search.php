<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sliderModule\models\SliderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-searchfields">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'name') ?>
    
    <?= $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'usertablebtn']) ?>
        <?= Html::a('Reset', ['index'], ['class' => 'usertablebtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

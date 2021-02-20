<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Page;

/* @var $this yii\web\View */
/* @var $model app\modules\sliderModule\models\Slide */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imageFile')->fileinput() ?>

    <!--
    <p>Enter an url</p>
    <?= $form->field($model, 'url')->textInput()->label(false) ?>

    <?= $form->field($model, 'target')->checkBox([0, 1], $model->target) ?>
    -->
    
    <div class="userzone">
        <?= Html::submitButton('Save', ['class' => 'userzonebtn save']) ?>
       
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>

$(function(){

    $("select#slide-page").change(function(){

        if($(this).val() !== 0){
            $("input#slide-url").val("");
        }

    });

    $("input#slide-url").change(function(){

        if($(this).val() !== 0){
            $("select#slide-page").val("");
        }
    
    });

});


</script>
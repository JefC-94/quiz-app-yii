<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Question */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inquiry')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= $form->field($model, 'answer')->textInput(['maxlength' => true]) ?>

    <!--<input style="margin-left:2em;" type="checkbox" id="multipleChoice">Make question multiple choice-->

    <p>Optional:</p>

    <?= $form->field($model, 'wrong_options')->textInput(['maxlength' => true]) ?>

    <div class="userzone">
        <?= Html::submitButton('Save', ['class' => 'userzonebtn save']) ?>
        <?php echo Html::a('Cancel', ['round/view', 'slug' => $model->round->slug], ['class'=>'userzonebtn back'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>

    $(function(){

        //$(".field-question-wrong_options").hide();

        $("#multipleChoice").change(function(){
            if($(".field-question-wrong_options").is(":visible")){
                $(".field-question-wrong_options").hide();
            } else {
                $(".field-question-wrong_options").show();
            }
        });
    });

</script>
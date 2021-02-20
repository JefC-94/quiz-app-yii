<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sliderModule\models\Slider */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>

    <input style="margin-left:2em;" type="checkbox" id="keepAspectRatio">Constrain aspect ratio

    <?= $form->field($model, 'gap')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lightbox')->checkBox([0, 1]) ?>

    <div class="userzone">
        <?= Html::submitButton('Save', ['class' => 'userzonebtn save']) ?>
        <?php echo Html::a('Cancel', ['slider/adminview', 'slug' => $model->slug], ['class'=>'userzonebtn back'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>

    $(function(){

        var aspect_ratio = "<?php echo $model->aspect_ratio; ?>";

        function checkAspectRatio(){
            if ($("#keepAspectRatio").is(':checked')){
                return true;
            } else {
                return false;
            }
        }

        $("#keepAspectRatio").change(function(){
            if(this.checked){
                var width = $("#slider-width").val();
                var height = parseInt($("#slider-width").val() / aspect_ratio);
                $("#slider-height").val(height);
            }
        });

        $("#slider-width").change(function(){
                
            if(checkAspectRatio()){
                var height = parseInt($("#slider-width").val() / aspect_ratio);
                $("#slider-height").val(height);
            }

        });

        $("#slider-height").change(function(){
            
            if(checkAspectRatio()){
                var width = parseInt($("#slider-height").val() * aspect_ratio);
                $("#slider-width").val(width);
            }

        });

        

    });



</script>
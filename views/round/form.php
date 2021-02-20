<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Round */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Ronde ' . $model->order_index . " | " . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rounds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container inner">

    <div class="team-header">
        <h1><?php echo $this->title ?></h1>
    </div>

    <div class="team-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->hiddenInput(['readonly' => true, 'maxlength' => true])->label(false) ?>

        <?php foreach($model->questions as $index => $question){ ?>

            <p>Vraag <?= ($index + 1); ?>
            <div class="question">
                <?= $form->field($records[$index], 'answer')->textInput(['maxlength' => true, 'placeholder' => ''])->label(false); ?>
                <input class='notes' placeholder="notitie...">
            </div>

        <? } ?>

        <!--<div class="form-group userzone">
            <?= Html::submitButton('Save', ['class' => 'userzonebtn save', 'data' => [
                'confirm' => 'Are you sure you want to delete this user?'
            ],
            ]); ?>
        </div>-->

        <?php ActiveForm::end(); ?>

        <div class="team-form-end">
            <button class="form-sendin" id="savebtn">Inzenden</button>
        </div>

        <div id="error-msg">
        </div>

    </div>

</div>

<script>

    $(function(){

        $("#savebtn").click(function(e){
            if(!confirm("Weet je zeker dat je de antwoorden wil insturen?")){
                return false;
            }
            
            const slug = "<?php echo $model->slug ?>";

            const records = [];

            document.querySelectorAll("#record-answer").forEach(function(el){
                records.push(el.value);
            });

            $.ajax({
                type: "POST",
                url: ['form?slug=' + slug],
                data: {
                    "Round" : {
                        'name' : slug,
                        'records' : records,
                    },
                },
                success: function(data){
                    console.log(data);
                }
            });

        });


    });




</script>
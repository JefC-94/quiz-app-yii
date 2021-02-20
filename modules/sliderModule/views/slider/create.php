<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sliderModule\models\Slider */

$this->title = 'Create Slider';
$this->params['breadcrumbs'][] = ['label' => 'Sliders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container inner">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gap')->textInput(['maxlength' => true])->input('gap', ['value' => "0"]) ?>

    <?= $form->field($model, 'lightbox')->checkBox([0, 1]) ?>

    <div class="userzone">
        <?= Html::submitButton('Save', ['class' => 'userzonebtn save']) ?>
        <?php echo Html::a('Cancel', ['slider/index'], ['class'=>'userzonebtn back'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>

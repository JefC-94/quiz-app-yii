<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\quizModule\models\Quiz */
/* @var $form yii\widgets\ActiveForm */

$sessionUser = Yii::$app->user->identity;

?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_by')->hiddenInput(['readonly' => true, 'value' => $sessionUser->profile->id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'userzonebtn save']) ?>
        <?php echo Html::a('Cancel', ['quiz/index'], ['class'=>'userzonebtn back'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

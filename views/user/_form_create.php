<?php

use yii\helpers\Html;
use kartik\password\PasswordInput;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Role;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->widget(PasswordInput::classname(), [
    'pluginOptions' => [
        'showMeter' => false,
        'toggleMask' => false
    ]]); ?>
    
    <?= $form->field($model, 'selectedRoles')->checkboxList(ArrayHelper::map(Role::getRoles(), 'id', 'rolename'), $model->selectedRoles); ?>

    <div class="userzone">
        <?= Html::submitButton('Save', ['class' => 'userzonebtn save']) ?>
        <?php echo Html::a('Cancel', ['user/index'], ['class'=>'userzonebtn back'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
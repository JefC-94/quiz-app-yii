<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Role;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

/* $this->title = 'Update Own: ' . $model->user->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user->id, 'url' => ['view', 'id' => $model->user->id]];
$this->params['breadcrumbs'][] = 'Update Own'; */
$sessionUser = Yii::$app->user->identity;
?>
<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <!-- indien er member-functionaliteit ingesteld is, moet dit een aparte stijling krijgen, want dan is deze pagina zichtbaar voor members -->
    <div class="edit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'old_password')->passwordInput(['maxlength' => true]) ?>

    <a id="changePw" class="userzonebtn" style="width:140px;margin:0 0 1em 1em;">Change Password</a>
    
    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>

    <div class="userzone">
        <?= Html::submitButton('Save', ['class' => 'userzonebtn save', 'name' => 'edit-button']) ?>
        <?php if($sessionUser->isMember()){
            echo Html::a('Cancel', ['/home'], ['class'=>'userzonebtn back']);
        } else {
            echo Html::a('Cancel', ['user/index'], ['class'=>'userzonebtn back']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    

</div>

<script>

$(".field-editform-new_password").hide();

$("#changePw").click(function(){
    $(".field-editform-new_password").toggle();
});

</script>
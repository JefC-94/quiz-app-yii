<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\MailSignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Mailcontact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container inner">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup for our mailing list:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'mailcontact-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'firstname')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'lastname')->textInput() ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Signup', ['class' => 'userzonebtn', 'name' => 'signup-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <?php if (Yii::$app->session->hasFlash('mailConfirmation')){ ?>
        <div class="alert alert-error">
            <p>Thanks for signing up for our mailing list!</p>
        </div>
    <?php } ?>

</div>

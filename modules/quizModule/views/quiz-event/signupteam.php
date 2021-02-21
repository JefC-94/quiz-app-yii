<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fullwh lobby-page">

    <div class="lobby-wrap">

        <h1 class="sr-only"><?= Html::encode($this->title) ?></h1>

        <p class="label">Kies een ploegnaam:</p>

        <div class="signup-form">

        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'ploegnaam']) ?>

            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11">
                    <?= Html::submitButton('Go!', ['class' => 'lobby', 'id' => 'signup-team', 'name' => 'signup-button']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>

        </div>

        <?php if (Yii::$app->session->hasFlash('errorSignup')){ ?>
            <div class="alert alert-error">
                <p>Deze ploegnaam is al gekozen!</p>
            </div>
        <?php } ?>
    </div>

</div>

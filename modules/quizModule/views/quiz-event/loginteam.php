<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="fullwh lobby-page">

    <div class="lobby-wrap">

        <h1 class="sr-only"><?= Html::encode($this->title) ?></h1>

        <p class="label">Login met je ploegnaam:</p>

        <div class="login-form">

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'ploegnaam']) ?>
            
            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <?php echo '<input id="loginform-location" type="hidden" name="location" value="';
            if(isset($_GET['url'])){ 
                echo htmlspecialchars($_GET['url']);
            }
            echo '" />';
            ?>

            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11">
                    <?= Html::submitButton('Login', ['class' => 'lobby', 'name' => 'login-button', 'id' => 'login-team']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>

        </div>
    
    </div>

</div>
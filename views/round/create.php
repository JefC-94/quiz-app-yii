<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Round */

$this->title = 'Create Round';
$this->params['breadcrumbs'][] = ['label' => 'Rounds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

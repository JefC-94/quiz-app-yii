<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Round */

$this->title = 'Update Round: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rounds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

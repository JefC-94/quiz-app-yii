<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Question */

$this->title = $model->round->name . ': create Question ' . $model->order_index;
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

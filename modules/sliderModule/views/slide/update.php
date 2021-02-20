<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sliderModule\models\Slide */

$this->title = 'Update Slide in: ' . $model->slider->article->slug;
$this->params['breadcrumbs'][] = ['label' => 'Slides', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="container inner">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <p style="margin-left:1em";>
    <?php echo Html::a('Cancel', ['slider/adminview', 'slug' => $model->slider->slug], ['class'=>'userzonebtn back'])?>
    </p>

</div>

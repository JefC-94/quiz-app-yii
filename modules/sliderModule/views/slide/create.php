<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sliderModule\models\Slide */

$this->title = 'Create Slide';
$this->params['breadcrumbs'][] = ['label' => 'Slides', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container inner">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    
    <p style="margin-left:1em";>
    <?php echo Html::a('Cancel', ['slider/adminview', 'slug' => $model->slug], ['class'=>'userzonebtn back'])?>
    </p>

</div>
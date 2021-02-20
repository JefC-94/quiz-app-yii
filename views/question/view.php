<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Question */

$this->title = "Vraag " . $model->order_index;
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$sessionUser = Yii::$app->user->identity;

?>
<div class="container inner">

    <p><?= Html::encode($model->round->name) ?></p>

    <h1><?php echo $this->title; ?></h1>

    <div class="userzone">
        <?php if($sessionUser->isAdmin()){
            echo Html::a('Back to Round', ['round/view', 'slug' => $model->round->slug], ['class' => 'userzonebtn']);
            echo Html::a('Update', ['update', 'slug' => $model->round->slug, 'id' => $model->id], ['class' => 'userzonebtn']);
            echo Html::a('Delete', ['delete', 'slug' => $model->round->slug, 'id' => $model->id], [
                'class' => 'userzonebtn delete',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        } ?>
    </div>

    <h2 class="question-inquiry">
        <?= $model->inquiry ?>
    </h2>

    <?= Html::img('@web/uploads/questions/'. $model->image, ['width' => '200']) ?>

    <?php if ($model->wrong_options){ ?>

        <?php
            $options = explode (",", $model->wrong_options);
            array_push($options, $model->answer);
            shuffle($options);
        ?>

        <div class="muliple-choice-wrap">
            <?php foreach($options as $option){ ?>
                <div class="option">
                    <?php echo $option; ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?> 

</div>
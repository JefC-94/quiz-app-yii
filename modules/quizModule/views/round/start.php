<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Round */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rounds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$sessionUser = Yii::$app->user->identity;

?>
<div class="fullwh round-start-wrap">

    <h1 style="text-align:center;font-size:30px;"><?php echo $this->title; ?></h1>

    <div class="slide-buttons">
        <?php
            echo Html::a('Start', ['viewquestion', 'slug' => $model->slug, 'order_index' => 1], ['class' => 'userzonebtn', 'id' => 'nextBtn']);
        ?>
    </div>

</div>

<script>

$(function(){

    $('html').keydown(function(e){
        const slug = "<?= $model->slug; ?>";
        const base = "<?= Url::base('http'); ?>";
        if(e.keyCode === 37){
            const url = base + "/round/previousround?slug=" + slug;
            window.location.href = url;
        }
        if(e.keyCode === 39){
            const url = base + "/round/viewquestion?slug=" + slug + "&order_index=1";
            console.log(url);
            window.location.href = url;
        }
    });

});

</script>

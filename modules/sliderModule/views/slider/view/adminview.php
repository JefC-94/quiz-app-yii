<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sliderModule\models\Slider */

$this->title = "Slider for " . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sliders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$sessionUser = Yii::$app->user->identity;
?>

<div class="container inner">

    <div class="back-to-index">
        <?php echo Html::a('Back to index', ['index'])?>
    </div>

    <div class="userzone">
        <?php if ($sessionUser->isEditor() or $sessionUser->isAdmin()){
        //echo Html::a('Update', ['update', 'slug' => $model->slug], ['class' => 'userzonebtn']);
        echo Html::a('Add Slide', ['../slide/create', 'slug' => $model->slug], ['class' => 'userzonebtn']);
        /* echo Html::a('Delete', ['delete', 'slug' => $model->slug], [
            'class' => 'userzonebtn delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]); */
        } ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('errorDelete')){ ?>
        <div class="alert alert-error" style="background-color:darkred;">
            <p style="color:white;">You cannot delete this slide. An article slider should always have at least one slide.</p>
        </div>
    <?php } ?>

    <div class="slider-overview">
        <?php foreach ($model->slides as $index => $slide){ ?>
            <div class='slide-container' style='width:250px;height:auto;'>

                <?php if($slide->url){     
                    ?> 
                    <a href='<?php echo $slide->url; ?>' target="<?php if($slide->target === 1){echo "_blank";} else echo ""; ?>">
                    <?php 
                } ?>
                    
                    <img src="../<?php echo $slide->image->imagepath ;?>" width="250" height="<?php echo 250 / $model->aspect_ratio; ?>">
                
                <?php if($slide->url){
                    echo "</a>";
                } ?>
                
                <div class='controls'>
                    <div class="actions">
                        <?php
                        if ($sessionUser->isEditor() or $sessionUser->isAdmin()){
                        echo Html::a('Update', ['/slide/update', 'slug' => $model->slug, 'id' => $slide->id], ['class' => 'userzonebtn']);
                        echo Html::a('Delete', ['/slide/delete', 'slug' => $model->slug, 'id' => $slide->id], [
                            'class' => 'userzonebtn delete',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                        }
                        ?>
                    </div>
                    <div class='move-controls'>
                        <div class="move left">
                        <?php
                        if ($sessionUser->isEditor() or $sessionUser->isAdmin()){
                        if($index !== 0){
                            echo Html::a('<<', ['/slide/moveslidefirst', 'slug' => $model->slug, 'id' => $slide->id], ['class' => 'arrow prev']);
                            echo Html::a('<', ['/slide/moveslidedown', 'slug' => $model->slug, 'id' => $slide->id], ['class' => 'arrow prev']);
                        }}
                        ?>
                        </div>
                        <div class="move right">
                        <?php
                        if ($sessionUser->isEditor() or $sessionUser->isAdmin()){
                        if($index !== $model->getCountSlides()-1){
                            echo Html::a('>', ['/slide/moveslideup', 'slug' => $model->slug, 'id' => $slide->id], ['class' => 'arrow next']);
                            echo Html::a('>>', ['/slide/moveslidelast', 'slug' => $model->slug, 'id' => $slide->id], ['class' => 'arrow next']);
                        }}
                        ?>
                        </div>
                    </div>
                </div>

            </div>
        <?php } ?>
    
    <?php
    if($model->getCountSlides() == 0){
        echo "<p class='noresults'>You have no slides in this slider yet. Add a slide with the button on the right.</p>";
    }  
    ?>

    </div>

    <div class="slider-options">
    
    <ul>
        <li>
            <p>Number of slides:</p>
            <p><?php echo $model->getCountSlides(); ?></p>
        </li>
        <li>
            <p>Dimensions:</p>
            <p><?php echo $model->width . "px | " . $model->height . "px" ; ?></p>
        </li>
        <li>
            <p>Gap:</p>
            <p><?php echo $model->gap . "px" ; ?></p>
        </li>
        <li>
            <p>When slide is clicked</p>
            <p><?php if($model->lightbox === 1){
                        echo "=> enlarge image";
                    } else {
                        echo "=> go to link";
                    } ?>
            </p>
        </li>
    
    </div>

</div>

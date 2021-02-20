<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sliderModule\models\Slider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sliders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<style>

    .flex-viewport{
        width:inherit;
        height:inherit;
    }

    .flex-direction-nav a:before {
        color: black;
    }

    .flex-direction-nav a:after {
        color: black;
    }

</style>

<?php if (count($model->slides) == 1){ ?>
    
    <?php echo Html::img('@web/'.$model->sliderImages[0], ['width' => '400px']); ?>

<?php } else { ?>

    <?php echo "<div class='flexslider'>"; ?>
        <?php echo "<ul class='slides'>"; ?>
            <?php foreach ($model->slides as $index => $slide){
                    ?>
                    <li>
                    <?php if ($model->lightbox === 1){
                        ?>
                            <a href="../<?php echo $slide->image->imagepath; ?>" data-lightbox="<?php echo $model->name; ?>">
                            <img src="../<?php echo $slide->image->imagepath ;?>">
                            </a>
                        <?php
                    } else {
                        ?>
                            <?php if($slide->url){     
                                ?>
                                <a href='../<?php echo $slide->url; ?>' target="<?php if($slide->target === 1){echo "_blank";} else echo ""; ?>">
                                <?php 
                            } ?>
                            
                            <img src="../<?php echo $slide->image->imagepath ;?>">                        
                            
                            <?php if($slide->url){
                                echo "</a>";
                            } ?>
                        <?php
                    }
                    ?>
                    </li>
                    <?php
                }
            ?>
        <?php echo "</ul>"; ?>
    </div>

<?php } ?>




<script>

    $(function(){
        
        $('.flexslider').flexslider({
            animation: "slide",
        });

    });

</script>


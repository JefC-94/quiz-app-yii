<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Question */

$this->title = $model->name . " | Vraag " . $question->order_index;
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$sessionUser = Yii::$app->user->identity;

?>
<div class="container inner slide-wrap">

        <p class="roundname"><?= Html::encode($model->name) ?></p>

        <div class="image-wrap">
            <?= Html::img('@web/uploads/questions/'. $question->image) ?>
        </div>

        <div class="question-wrap">

            <h1><?php echo "Vraag " . $question->order_index; ?></h1>

            <h2 class="question-inquiry">
                <?= $question->inquiry ?>
            </h2>

            <h3><? if($solution === true){
                    echo $question->answer;
                }?>
            </h3>

            <?php if ($question->wrong_options && !$solution){ ?>

                <?php
                    $options = explode (",", $question->wrong_options);
                    array_push($options, $question->answer);
                    shuffle($options);
                ?>

                <div class="multiple-choice-wrap">
                    <?php foreach($options as $option){ ?>
                        <div class="option">
                            <?php echo $option; ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>

    <div class="slide-buttons">
    <?php if($sessionUser->isAdmin()){
        if($solution === true){
                echo Html::a('Previous', ['viewsolution', 'slug' => $model->slug, 'order_index' => $question->order_index - 1], ['class' => 'userzonebtn', 'id' => 'prevBtn']);
                echo Html::a('Next', ['viewsolution', 'slug' => $model->slug, 'order_index' => $question->order_index + 1], ['class' => 'userzonebtn', 'id' => 'nextBtn']);
        } else {
            echo Html::a('Previous', ['viewquestion', 'slug' => $model->slug, 'order_index' => $question->order_index - 1], ['class' => 'userzonebtn', 'id' => 'prevBtn']);
            //if this is the last question -> go to solutions!
            if($question->order_index !== count($model->questions)){
                echo Html::a('Next', ['viewquestion', 'slug' => $model->slug, 'order_index' => $question->order_index + 1], ['class' => 'userzonebtn', 'id' => 'nextBtn']);
            } else {
                echo Html::a('View Solutions', ['solutions', 'slug' => $model->slug], ['class' => 'userzonebtn', 'id' => 'nextBtn']);
            }
        }
    } ?>
    </div>

</div>


<script>

$(function(){

    $('html').keydown(function(e){
        const slug = "<?= $model->slug; ?>";
        const base = "<?= Url::base('http'); ?>";
        const solution = "<?= $solution; ?>";
        const highestIndex = "<?= count($model->questions) ?>";
        if(e.keyCode === 37){
            const order_index = "<?= $question->order_index - 1; ?>";
            let url = base + "/round/viewquestion?slug=" + slug + "&order_index=" + order_index;
            if(solution){
                if(order_index == 0){
                    url = base + '/round/solutions?slug=' + slug;
                } else {
                    url = base + "/round/viewsolution?slug=" + slug + "&order_index=" + order_index;
                }
            }
            window.location.href = url;
        }
        if(e.keyCode === 39){
            const order_index = "<?= $question->order_index + 1; ?>";
            let url = base + "/round/viewquestion?slug=" + slug + "&order_index=" + order_index;
            if(order_index === highestIndex-1){
                url = base + "/round/solutions?slug=" + slug;
            }
            if(solution){
                url = base + "/round/viewsolution?slug=" + slug + "&order_index=" + order_index;
            }
            window.location.href = url;
        }
    });

});

</script>
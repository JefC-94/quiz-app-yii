<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Team */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container inner">

<h1 class="admin"><?= Html::encode($this->title) ?></h1>

<div class="admin-table-wrapper">
    <table class="admin compact">
        <tr>
            <td>Username</td><td><?= $model->username ?></td>
        </tr> 
        <tr>
            <td>Score</td><td><?= $model->score ?></td>
        </td>
    </table>
</div>

<div style="height:100px;"></div>    

<div class="records">

    <table class="admin">
        <thead>
            <th>Ronde</th>
            <th>Vraag</th>
            <th>Invoer</th>
            <th>Correct antwoord</th>
            <th>Acties</th>
        </thead>
        <?php foreach($model->record as $record){ ?>
            <tr>
                <td> <?= $record->round->name; ?></td>
                <td style="min-width:30px;"> <?= $record->order_index; ?></td>
                <td> <?= $record->answer; ?></td>
                <td> <?= $record->question->answer; ?></td>
                <td>
                    <p style="display:inline;" id="correct"><?= $record->correct; ?></p>
                    <?php echo Html::a('Mark correct', ['user/assess', 'id' => $model->id, 'rec_id' => $record->id, 'correct' => true], ['class' => 'usertablebtn true', 'id' => $record->id]) ?>
                    <?php echo Html::a('Mark wrong', ['user/assess', 'id' => $model->id, 'rec_id' => $record->id, 'correct' => false], ['class' => 'usertablebtn false', 'id' => $record->id]) ?>
                </td>
            </tr>
        <?php } ?>
    </table>

</div>

</div>

<script>

$(function(){


    function calcScore(){

        const scores = document.querySelectorAll("#correct");

        let score = 0;

        scores.forEach(el => {
            score += +el.innerHTML;
        });

        document.querySelector("#score").innerHTML = score;

    }

    calcScore();

    $(".true").click(function(e){
        e.preventDefault();

        const userid = "<?php echo $model->id ?>";

        $.ajax({
            type: "GET",
            url: ['assess?id=' + userid + '&rec_id=' + e.target.id + '&correct=1'],
            success: function(data){
                e.target.previousElementSibling.innerHTML = data;
                calcScore();
            }
        });
    });

    $(".false").click(function(e){
        e.preventDefault();

        const userid = "<?php echo $model->id ?>";

        $.ajax({
            type: "GET",
            url: ['assess?id=' + userid + '&rec_id=' + e.target.id + '&correct=0'],
            success: function(data){
                e.target.previousElementSibling.previousElementSibling.innerHTML = data;
                calcScore();
            }
        });
    });

});

</script>
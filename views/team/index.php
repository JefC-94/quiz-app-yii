<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Teams';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container inner">

<h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="admin-table-wrapper">
    <table class="admin lastcolumnwide">

        <thead>
            <tr>
                <th>Username</th>
                <th>Completed Rounds</th>
                <th>Score</th>
        </thead>
        <tbody>
            <?php
                $teams = $dataProvider->getModels();

                if(count($teams) == 0 ){ ?>
                    <script>
                        $(".noresults").show();
                    </script>
                <?php
                }

                foreach($teams as $team){
                    $id = $team->id;
                    $username = $team->username;
                    $score = $team->score;
            ?>
                <tr>
                    <td>
                        <a class="link" href="<?php echo \yii\helpers\Url::to(['/team/view', 'id' => $id]) ?>"><?= $username ?></a>
                    </td>
                    <td>
                        <?php foreach($team->getCompletedRounds($id) as $round){echo $round->order_index . "  ";} ?>
                    </td>
                    <td>
                        <?= $score ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
                    
    <?php if(count($teams) === 0){
        echo "<p class='noresults'>No users found</p>";
    } ?>

    <?php echo LinkPager::widget([
        'pagination' => $pages,
    ]); ?>

    </div>


</div><!--end of user container -->


</div>

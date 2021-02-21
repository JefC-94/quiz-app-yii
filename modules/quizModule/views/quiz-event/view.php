<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $model app\modules\quizModule\models\QuizEvent */

$this->title = $model->quiz->name . " on " . Yii::$app->formatter->asDate($model->created_at);
$this->params['breadcrumbs'][] = ['label' => 'Quiz Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <div class="userzone">
        <?= Html::a('Back to quiz events', ['quiz-event/index'], ['class' => 'userzonebtn']); ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'userzonebtn']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'userzonebtn delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <div class="admin-table-wrapper">
    <table class="admin lastcolumnwide">

        <thead>
            <tr>
                <th>Username</th>
                <th>Completed Rounds</th>
                <th>Score</th>
        </thead>
        <tbody>
            <?php foreach($teams as $team){
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

</div>

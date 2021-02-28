<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\quizModule\models\QuizEventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quiz Events';
$this->params['breadcrumbs'][] = $this->title;

$sessionUser = Yii::$app->user->identity;

?>
<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <div class="userzone">
        <?= Html::a('Create Quiz Event', ['create'], ['class' => 'userzonebtn create']) ?>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="admin-table-wrapper">
    <table class="admin lastcolumnwide">

        <thead>
            <tr>
                <th>Unique ID</th>
                <th>Quiz</th>
                <th>Number of Teams</th>
                <?php if($sessionUser->isAdmin()){
                    echo "<th>Actions</th>";
                }
                ?>
        </thead>
        <tbody>
            <?php
                $quizEvents = $dataProvider->getModels();

                foreach($quizEvents as $quizEvent){
                    $id = $quizEvent->id;
                    $uuid = $quizEvent->uuid;
                    $quiz = $quizEvent->quiz;
            ?>
                <tr>
                    <td>
                        <a class="link" href="<?php echo \yii\helpers\Url::to(['view', 'id' => $id]) ?>"><?= $uuid ?></a>
                    </td>
                    <td>
                        <a class="link" href="<?php echo \yii\helpers\Url::to(['quiz/view', 'slug' => $quiz->slug]) ?>"><?= $quiz->name ?></a>
                    </td>
                    <td><p><?php echo count($quizEvent->teams); ?></p></td>
                    <?php if($sessionUser->isAdmin()){
                        echo "<td>";
                        echo Html::a('Start quiz', ['startquiz', 'id' => $id], ['class' => 'usertablebtn']);
                        echo Html::a('Delete', ['delete', 'id' => $id], [
                            'class' => 'usertablebtn delete',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this user?',
                                'method' => 'post',
                            ],
                        ]);
                        echo "</td>";
                    } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
                    
    <?php if(count($quizEvents) === 0){
        echo "<p class='noresults'>No rounds found</p>";
    } ?>


    <?php echo LinkPager::widget([
        'pagination' => $pages,
    ]); ?>

    </div>


</div>

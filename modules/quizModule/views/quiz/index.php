<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\quizModule\models\QuizSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quizzes';
$this->params['breadcrumbs'][] = $this->title;

$sessionUser = Yii::$app->user->identity;

?>
<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <div class="userzone">
        <?= Html::a('Create Quiz', ['create'], ['class' => 'userzonebtn create']) ?>
    </div>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="admin-table-wrapper">
    <table class="admin">

        <thead>
            <tr>
                <th>Quiz</th>
                <th>Number of Rounds</th>
                <th>Created By</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $quizzes = $dataProvider->getModels();

                if(count($quizzes) == 0 ){ ?>
                    <script>
                        $(".noresults").show();
                    </script>
                <?php
                }

                foreach($quizzes as $quiz){
                    $id = $quiz->id;
                    $name = $quiz->name;
                    $slug = $quiz->slug;
                    $createdBy = $quiz->createdBy;
                    $rounds = $quiz->rounds;
            ?>
                <tr>
                    <td>
                        <a class="link" href="<?php echo \yii\helpers\Url::to(['view', 'slug' => $slug]) ?>"><?= $name ?></a>&nbsp;
                        <?php if($sessionUser->id == $createdBy->user->id){
                            echo Html::a('<span class="fa fa-pencil"></span>', ['update', 'slug' => $slug], ['class' => 'index']);
                        } ?>
                    </td>
                    <td><?= count($rounds) ?></td>
                    <td><?= $createdBy->name ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
                    
    <?php if(count($quizzes) === 0){
        echo "<p class='noresults'>No users found</p>";
    } ?>

    <?php echo LinkPager::widget([
        'pagination' => $pages,
    ]); ?>

    </div>


</div>

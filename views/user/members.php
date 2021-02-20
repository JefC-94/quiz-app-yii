<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Teams';
$this->params['breadcrumbs'][] = $this->title;
$sessionUser = Yii::$app->user->identity;
?>

<div class="container inner">

    <h1 class="admin">Teams</h1>

    <?php echo $this->render('_search_members', ['model' => $searchModel]); ?>

    <div class="admin-table-wrapper">
    <table class="admin">

        <thead>
            <tr>
                <th>Username</th>
                <th>Completed</th>
                <th>Score</th>
                <?php if($sessionUser->isAdmin()){
                    echo "<th>Actions</th>";
                }
                ?>
        </thead>
        <tbody>
            <?php
                $users = $dataProvider->getModels();

                foreach($users as $user){
                    $id = $user->id;
                    $username = $user->username;
                    
            ?>
                <tr>
                    <td>
                        <a class="link" href="<?php echo \yii\helpers\Url::to(['/user/view', 'id' => $id]) ?>"><?= $username ?></a>&nbsp;
                        <?php if($sessionUser->id == $id){
                            echo Html::a('<span class="fa fa-pencil"></span>', ['edit', 'id' => $id], ['class' => 'index']);
                        } ?>
                    </td>
                    <td>
                        <?php foreach($user->getCompletedRounds($id) as $round){echo $round->order_index . "  ";} ?>
                    </td>
                    <td><?php echo $user->score ?></td>
                    <?php if($sessionUser->isAdmin()){
                        echo "<td>";
                        echo Html::a('Delete', ['delete', 'id' => $id], [
                            'class' => 'usertablebtn delete',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this member?',
                                'method' => 'post',
                            ],
                        ]);
                        echo "</td>";
                    } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if(count($users) === 0){
        echo "<p class='noresults'>No users found</p>";
    } ?>

    <?php echo LinkPager::widget([
        'pagination' => $pages,
    ]); ?>

    </div>

    
    
</div><!--end of user container -->

<script>

$(".noresults").hide();

</script>
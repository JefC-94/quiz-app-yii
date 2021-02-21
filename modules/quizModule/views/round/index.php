<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoundSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rounds';
$this->params['breadcrumbs'][] = $this->title;

$sessionUser = Yii::$app->user->identity;

?>
<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <div class="userzone">
        <?= Html::a('Create Round', ['create'], ['class' => 'userzonebtn']) ?>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="admin-table-wrapper">
    <table class="admin lastcolumnwide">

        <thead>
            <tr>
                <th>Index</th>
                <th>Round Name</th>
                <th>Number of Questions</th>
                <th>Move</th>
                <?php if($sessionUser->isAdmin()){
                    echo "<th>Actions</th>";
                }
                ?>
        </thead>
        <tbody>
            <?php
                $rounds = $dataProvider->getModels();

                foreach($rounds as $round){
                    $id = $round->id;
                    $name = $round->name;
                    $slug = $round->slug;
                    $order_index = $round->order_index;
            ?>
                <tr>
                    <td><?php echo $order_index; ?></td>
                    <td>
                        <a class="link" href="<?php echo \yii\helpers\Url::to(['view', 'slug' => $slug]) ?>"><?= $name ?></a>
                    </td>
                    <td><p><?php echo count($round->questions); ?></p></td>
                    <td class='move'>
                    <div class="move-one">
                        <?php 
                        if($order_index !== 1){
                            echo Html::a('<span class="fa fa-chevron-up"></span>', ['moveitem', 'pos' => 'down', 'slug' => $slug], ['class' => 'arrow prev']);
                        }
                        if($order_index == $highest_index){} else {
                            echo Html::a('<span class="fa fa-chevron-down"></span>', ['moveitem', 'pos' => 'up', 'slug' => $slug], ['class' => 'arrow next']);
                        } 
                        ?>
                        </div>
                        <div class="move-all">
                        <?php 
                        if($order_index !== 1){
                            echo Html::a('<span class="fa fa-chevron-up"></span><span class="fa fa-chevron-up"></span>', ['moveitem', 'pos' => 'first', 'slug' => $slug], ['class' => 'arrow prev']);
                        }
                        if($order_index == $highest_index){} else {
                            echo Html::a('<span class="fa fa-chevron-down"></span><span class="fa fa-chevron-down"></span>', ['moveitem', 'pos' => 'last', 'slug' => $slug], ['class' => 'arrow next']);
                        }
                        ?>
                    </div>
                </td>
                    <?php if($sessionUser->isAdmin()){
                        echo "<td>";
                        echo Html::a('Update', ['update', 'slug' => $slug], ['class' => 'usertablebtn']);
                        echo Html::a('Delete', ['delete', 'slug' => $slug], [
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
                    
    <?php if(count($rounds) === 0){
        echo "<p class='noresults'>No rounds found</p>";
    } ?>


    <?php echo LinkPager::widget([
        'pagination' => $pages,
    ]); ?>

    </div>


</div>

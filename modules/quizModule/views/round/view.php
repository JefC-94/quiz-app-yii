<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Round */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rounds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$sessionUser = Yii::$app->user->identity;

?>
<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <div class="userzone">
        <?= Html::a('Back to quiz', ['quiz/view', 'slug' => $model->quiz->slug], ['class' => 'userzonebtn']); ?>
        <?= Html::a('Add Question', ['question/create', 'slug' => $model->slug], ['class' => 'userzonebtn']); ?>

        <?= Html::a('Update', ['update', 'slug' => $model->slug], ['class' => 'userzonebtn']) ?>
        <?= Html::a('Delete', ['delete', 'slug' => $model->slug], [
            'class' => 'userzonebtn delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin">
        <thead>
            <th>Index</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Move</th>
            <th>Actions</th>
        </thead>
        <tbody>
        <?php foreach($model->questions as $index => $question){ 
            $order_index = $question->order_index;
            $id = $question->id;
            $inquiry = $question->inquiry;
            $answer = $question->answer;
        ?>
            <tr class="question-container">
                <td><?php echo $order_index; ?></td>
                <td><a class="link" href="<?php echo \yii\helpers\Url::to(['question/view', 'slug' => $model->slug, 'order_index' => $order_index]) ?>"><?= $inquiry ?></a></td>
                <td><?php echo $answer; ?></td>
                <td class='move'>
                    <div class="move-one">
                        <?php 
                        if($order_index !== 1){
                            echo Html::a('<span class="fa fa-chevron-up"></span>', ['/question/moveitem', 'pos' => 'down', 'id' => $id], ['class' => 'arrow prev']);
                        }
                        if($order_index == $highest_index){} else {
                            echo Html::a('<span class="fa fa-chevron-down"></span>', ['/question/moveitem', 'pos' => 'up', 'id' => $id], ['class' => 'arrow next']);
                        } 
                        ?>
                        </div>
                        <div class="move-all">
                        <?php 
                        if($order_index !== 1){
                            echo Html::a('<span class="fa fa-chevron-up"></span><span class="fa fa-chevron-up"></span>', ['/question/moveitem', 'pos' => 'first', 'id' => $id], ['class' => 'arrow prev']);
                        }
                        if($order_index == $highest_index){} else {
                            echo Html::a('<span class="fa fa-chevron-down"></span><span class="fa fa-chevron-down"></span>', ['/question/moveitem', 'pos' => 'last', 'id' => $id], ['class' => 'arrow next']);
                        }
                        ?>
                    </div>
                </td>
                <td>
                    <?php 
                    echo Html::a('Update', ['question/update', 'slug' => $model->slug, 'id' => $question->id], ['class' => 'userzonebtn']);
                    echo Html::a('Delete', ['question/delete', 'slug' => $model->slug, 'id' => $question->id], [
                        'class' => 'userzonebtn delete',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]);
                    ?>
                </td>
            </tr>
        <?php } ?>        
        </tbody>
        </table>
    </div>

    <div class="">
        <?php 
        echo Html::a('Start Round', ['start', 'slug' => $model->slug], ['class' => 'userzonebtn']);
        ?>
    </div>

</div>

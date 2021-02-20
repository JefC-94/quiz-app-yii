<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sliderModule\models\SliderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sliders';
$this->params['breadcrumbs'][] = $this->title;

$sessionUser = Yii::$app->user->identity;
?>
<div class="container inner">

    <div class="userzone">
        <?php if ($sessionUser->isAdmin() or $sessionUser->isEditor()){
        echo Html::a('Create Slider', ['create'], ['class' => 'userzonebtn']);
        } ?>
    </div>

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="admin-table-wrapper">
    <table class="admin"> 

        <thead>
            <tr>
                <th>Slidername</th>
                <?php if ($sessionUser->isAdmin() or $sessionUser->isEditor()){
                    echo "<th>Actions</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>

        <?php

            $sliders = $dataProvider->getModels();

            foreach($sliders as $slider){
                $name = $slider->name;
                $slug = $slider->slug;
            ?>

            <tr>
                <td><a class="link" href="<?php echo \yii\helpers\Url::to(['slider/adminview', 'slug' => $slug]) ?>"><?= $name ?></a></td>
                <?php if ($sessionUser->isAdmin() or $sessionUser->isEditor()){
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

        <?php if(count($sliders) === 0){
            echo "<p class='noresults'>No sliders found</p>";
        } ?>

        <?php echo LinkPager::widget([
                'pagination' => $pages,
        ]); ?>

        </div>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = $model->firstname . " " . $model->lastname;
$this->params['breadcrumbs'][] = ['label' => 'Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$sessionUser = Yii::$app->user->identity;
?>

<div class="container inner">

    <div class="userzone">
        <?php if(!Yii::$app->user->isGuest){ ?>
        <?php if($sessionUser->id == $model->user_id){ ?>
            <?= Html::a('Update', ['update', 'slug' => $model->slug], ['class' => 'userzonebtn']) ?>
        <?php }} ?>
    </div>

    <div class="back-to-index">
        <?php 
            echo Html::a('Back to blog', ['/blog']);
            if(!Yii::$app->user->isGuest){
                echo Html::a('Back to profiles', ['profile/index']);
            }
        ?>
    </div>

    <div class="profile-wrap">
        
        <div class="profile-content">
            <h1 class="profile-title"><?= Html::encode($this->title) ?></h1>

            <p class="bio"><?= Html::encode($model->bio) ?></p>

            <p><?= Html::encode($model->company) ?></p>
        </div>
        <div class="profile-image">
            <?= Html::img('@web/'.$model->image); ?>
        </div>
    </div>

    <div class="blog-wrap inner">
        <h4>More articles from <?= Html::encode($this->title) ?></h4>

        <div class="blogs">

        <?php 

        foreach($model->posts as $post){
            $title = \yii\helpers\Html::encode($post->title);
            $slug = $post->slug;
            $image = $post->featured_image;
            $created_at = Yii::$app->formatter->asRelativeTime($post->created_at);
            $username = $post->profile->firstname . " " . $post->profile->lastname;
            $categories = $post->postCats;

            if($post->draft == 0){
        ?>
            <div class="blog-box">
                    <a href="<?php echo \yii\helpers\Url::to(['post/' . $slug]) ?>">
                        <div class="blog-box-image">
                            <div>
                            <?php foreach($categories as $cat){
                                echo "<p>";
                                echo $cat->catnaam;
                                echo "</p>";
                                } ?>
                            </div>
                            <?= Html::img('@web/'.$image, ['width' => '500']); ?>
                        </div> 
                        <div class="blog-box-content">
                            <div class="above">
                                <h5><?php echo $title ?></h5>
                            </div>
                            <div class="below">
                                <p><?php echo "by " . $username ?></p>
                                <p><?php echo $created_at ?></p>
                            </div>
                        </div>
                    </a>
                </div>
        <?php 
            }
        } 
        ?>

        </div>
    </div>

</div><!-- end of container inner -->

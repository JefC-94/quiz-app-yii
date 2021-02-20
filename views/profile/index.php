<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Profiles';
$this->params['breadcrumbs'][] = $this->title;
$sessionUser = Yii::$app->user->identity;
?>

<div class="container inner">

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="admin-table-wrapper">
    <table class="admin profiles">
        
        <thead>
            <tr>
                <th>Name</th>
                <th>Bio</th>
                <th>Company</th>
                <th>Image</th>
                <th>User</th>
                <th>Posts</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $profiles = $dataProvider->getModels();

                foreach($profiles as $profile){
                    $id = $profile->id;
                    $name = \yii\helpers\Html::encode($profile->name);
                    $firstname = $profile->firstname;
                    $lastname = $profile->lastname;
                    $slug = $profile->slug;
                    $image = $profile->image;
                    $bio = $profile->bio;
                    $company = $profile->company;
                    if($profile->user){ $user = $profile->user;} else {$user = null;}
            ?>
                <tr>
                    <td>
                        <a class="link" href="<?php echo Url::to(['profile/view', 'slug' => $slug]) ?>">
                            <?php echo $firstname . " " . $lastname; ?>
                        </a>
                    </td>
                    <td><?php echo \yii\helpers\StringHelper::truncateWords($bio, 5); ?></td>
                    <td><?php echo $company ?></td>
                    <td><?= Html::img('@web/'.$image); ?></td>
                    <td>
                        <?php if ($profile->user){ ?>
                        <a class="link" href="<?php echo Url::to(['/user/view', 'id' => $user->id]) ?>"><?php echo $user->username ?></a>
                        <?php } ?>
                    </td>
                    <td><?php echo count($profile->posts); ?></td>
                    <td>
                        <?php 
                            if($sessionUser->isAdmin() and !$profile->user){
                                echo Html::a('Delete', ['delete', 'slug' => $slug], [
                                    'class' => 'usertablebtn delete',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this profile? This will delete all posts by this profile.',
                                        'method' => 'post',
                                    ],
                                ]);
                            };
                            if($sessionUser->id == $profile->user_id){
                                echo Html::a('Update', ['update', 'slug' => $profile->slug], ['class' => 'usertablebtn']);
                            };
                        ?>
                    </td>
                </tr>

            <?php } ?>
        </tbody>

    </table>

    <?php if(count($profiles) === 0){
        echo "<p class='noresults'>No profiles found</p>";
    } ?>

    <?php echo LinkPager::widget([
        'pagination' => $pages,
    ]); ?>

    </div>

    <?php if (Yii::$app->session->hasFlash('errorCreate')){ ?>
        <div class="alert alert-error">
            <p>This user already has an existing profile linked.</p>
        </div>
    <?php } ?>

</div><!--end of container inner -->
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
$sessionUser = Yii::$app->user->identity;
?>

<div class="container inner">

    <div class="userzone">
    <?php if($sessionUser->isAdmin()){ ?>
        <?= Html::a('Create User', ['create'], ['class' => 'userzonebtn']) ?>
    <?php } ?>
    </div>

    <h1 class="admin"><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="admin-table-wrapper">
    <table class="admin lastcolumnwide">

        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Profile</th>
                <th>Roles</th>
                <?php if($sessionUser->isAdmin()){
                    echo "<th>Actions</th>";
                }
                ?>
        </thead>
        <tbody>
            <?php
                $users = $dataProvider->getModels();

                if(count($users) == 0 ){ ?>
                    <script>
                        $(".noresults").show();
                    </script>
                <?php
                }

                foreach($users as $user){
                    $id = $user->id;
                    $username = $user->username;
                    $email = $user->email;
                    if($user->profile){
                        $profile = $user->profile->slug;
                    } else {
                        $profile = null;
                    }
                    
            ?>
                <tr>
                    <td>
                        <a class="link" href="<?php echo \yii\helpers\Url::to(['/user/view', 'id' => $id]) ?>"><?= $username ?></a>&nbsp;
                        <?php if($sessionUser->id == $id){
                            echo Html::a('<span class="fa fa-pencil"></span>', ['edit', 'id' => $id], ['class' => 'index']);
                        } ?>
                    </td>
                    <td><p><a class="mail" href="<?php echo "mailto:" . $email ?>"><?= $email ?></a></p></td>
                    <td>
                        <a class="mail" href="<?php echo \yii\helpers\Url::to(['profile/' . $profile]) ?>"><?php echo $profile ?></a>&nbsp;
                        <?php if($sessionUser->id == $id){
                            if($profile == null){
                                echo Html::a('create profile', ['/profile/create', 'user_id' => $id], ['class' => 'usertablebtn index']);
                            } else {
                            echo Html::a('<span class="fa fa-pencil"></span>', ['/profile/update?slug=' . $profile], ['class' => 'index']);
                        }} ?>
                    </td>
                    <td>
                        <?php foreach($user->userRole as $user_role){echo $user_role->role->rolename . " ";} ?>
                    </td>
                        <?php if($sessionUser->isAdmin()){
                            echo "<td>";
                            echo Html::a('Update Roles', ['update', 'id' => $id], ['class' => 'usertablebtn']);
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
                    
    <?php if(count($users) === 0){
        echo "<p class='noresults'>No users found</p>";
    } ?>

    <?php echo LinkPager::widget([
        'pagination' => $pages,
    ]); ?>

    </div>


</div><!--end of user container -->

<script>


</script>
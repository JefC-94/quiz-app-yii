<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Button;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\Controller;

AppAsset::register($this);

Yii::$app->user->setReturnUrl(Yii::$app->request->getUrl());

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

    <!-- USER PANEL FOR ADMINS, EDITORS AND AUTHORS -->
    <?php 
        $sessionUser = Yii::$app->user->identity;
        if (!Yii::$app->user->isGuest && !$sessionUser->isMember()){
    ?>
        <div class="userpanel" id="userpanel">
            <div class="platformsection user">
                <?php echo Html::a(''. $sessionUser->username . '' , ['/user/view', 'id' => $sessionUser->id], ['class' => 'userpanelbtn self userbtn']); ?>
                <button class="userdropdownbtn username"><?php echo $sessionUser->username; ?></button>
                <div class="sectionoptions user">
                    <ul class="list-unstyled">
                        <li><?php echo Html::a('Edit' , ['/user/edit', 'id' => $sessionUser->id], ['class' => 'userpanelbtn option']); ?> </li>
                        <li><?php echo Html::a('Logout' , ['/logout'], ['class' => 'userpanelbtn option', 'data' => ['method' => 'post']]); ?></li>
                    </ul>
                </div>
            </div>
            <div class="platformsection standard">
                <button class="userdropdownbtn">Platform</button>
                <div class="sectionoptions standard">
                    <ul class="list-unstyled">
                        <li class="<?php if($this->title == 'Users'){echo 'active';} ?>"><?php echo Html::a('Users', ['/user/index'], ['class' => 'userpanelbtn']); ?></li>
                        <li class="<?php if($this->title == 'Quizzes'){echo 'active';} ?>"><?php echo Html::a('Quizzes', ['/quiz/index'], ['class' => 'userpanelbtn']); ?></li>
                        <li class="<?php if($this->title == 'Quiz Events'){echo 'active';} ?>"><?php echo Html::a('Quiz Events', ['/quiz-event/index'], ['class' => 'userpanelbtn']); ?></li>
                        <li class="<?php if($this->title == 'Rounds'){echo 'active';} ?>"><?php echo Html::a('Rounds', ['/round/index'], ['class' => 'userpanelbtn']); ?></li>
                    </ul>    
                </div>
            </div>
            <div class="platformsection dropdown">
                <button class="userdropdownbtn">Pages</button>
                <div class="sectionoptions dropdown">
                    <ul class="list-unstyled">
                        <li class="<?php if($this->title == 'Home'){echo 'active';} ?>"><?php echo Html::a('Home', ['/home'], ['class' => 'userpanelbtn'])?></li>
                    </ul>
                </div>
            </div>
            <div class="platformsection dropdown">
                <button class="userdropdownbtn">Tools</button>
                <div class="sectionoptions dropdown">
                    <ul class="list-unstyled">
                        <li class="<?php if($this->title == 'Popups'){echo 'active';} ?>"><?php echo Html::a('Popups', ['/popup/index'], ['class' => 'userpanelbtn']);?></li>        
                        <li class="<?php if($this->title == 'Sliders'){echo 'active';} ?>"><?php echo Html::a('Sliders', ['/slider/index'], ['class' => 'userpanelbtn']);?></li>
                    </ul>
                </div>
            </div>
        </div> 
    <?php } ?>
    <!--END OF USERPANEL -->
    
    <div class="sitezone">

        <?php if (!Yii::$app->user->isGuest){ ?>
            <button class="userzonebtn" id="toggleUserzone">Hide panel</button>
        <?php } ?>

        <!-- COMMENT THIS DIV FOR PRODUCTION? KEEP IT FOR DEVELOPMENT ENVIRONMENT -> EASY LOGIN-LOGOUT -->
        <nav class="memberzone">
            <div class="member-options">
            <?php if(!Yii::$app->team->isGuest){
                echo Yii::$app->team->identity->username;
            } ?>
            
            </div>
        </nav>

        <main>
            <?= $content ?>
        </main>

        <footer class="footer">
                <p class="copyright">
                    
                    <?php 
                        echo "© " . date("Y") . " Jef Ceuppens";
                    ?>
                </p>
                <?php
                //if(Yii::$app->user->isGuest && Yii::$app->team->isGuest){
                    echo Html::a('Login', ['/login'], ['class' => 'memberzonebtn', 'data' => ['method' => 'post']]);
                    echo "&nbsp&nbsp&nbsp";
                    echo Html::a('Signup', ['/signup'], ['class' => 'memberzonebtn', 'data' => ['method' => 'post']]);
                    echo "&nbsp&nbsp&nbsp";
                //}
                //if(Yii::$app->user->isGuest && !Yii::$app->team->isGuest){
                    echo Html::a('Team Logout' , ['/teamlogout'], ['class' => 'memberzonebtn', 'data' => ['method' => 'post']]);
                //}
                if(!Yii::$app->user->isGuest && Yii::$app->team->isGuest){
                    echo "&nbsp&nbsp&nbsp";
                    echo Html::a('Logout' , ['/logout'], ['class' => 'memberzonebtn', 'data' => ['method' => 'post']]);
                }
                ?>
        </footer>

        <!-- SITE WIDE POP-UPS -->

        <div id="newsletter-signup" class="popup-container popup-center" style="display:none;"></div>

    </div> <!-- END OF SITEZONE -->

    <?= bizley\cookiemonster\CookieMonster::widget([
        'content' => array(
        'buttonMessage' => 'I Accept', // instead of default 'I understand'
        ),
        'mode' => 'top'
    ]) ?>

<?php $this->endBody() ?>
</body>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->

<script>

$(function(){

    //if(localStorage.getItem('newsletter-signup-state') != 'shown'){
    //    setTimeout(function(){openPopup('newsletter-signup', 'fade', 300);},200);
    //    localStorage.setItem('newsletter-signup-state', 'shown');
    //}

});

</script>

</html>

<?php $this->endPage() ?>

<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use app\models\userforms\LoginForm;
use app\models\userforms\LoginTeamForm;
use app\models\userforms\SignupForm;
use app\models\userforms\SignupTeamForm;
use app\models\Round;
use app\models\Record;
use app\models\User;
use app\models\Profile;
use app\models\ContactForm;
use app\modules\mailcontactModule\models\MailContact;
use app\modules\mailcontactModule\models\MailSignupForm;

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'denyCallback' => function ($rule, $action) {
                    return Yii::$app->getResponse()->redirect(['/home'],302);
                    //throw new ForbiddenHttpException('You are not allowed to access this page');
                },
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    //** REDIRECT GUESTS, MEMBERS AND ADMIN USERS TO THE RIGHT PAGES ---------- */

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('home');
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionHome()
    {
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->isMember()){
                $this->actionActiveround();
            } else {
                return $this->render('home-admin');
            }
        }

        return $this->render('home');
    }

    /**
     * Quiz is over -> thanks page
     * 
     */
    public function actionEnd()
    {
        return $this->render('end');
    }


    //** SIGNUP AND LOGIN ADMIN USERS ACTIONS --------------------------------------- */

    /**
     * Login action for admin users
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if(isset($_POST['location'])){
            $return_url = htmlspecialchars($_POST['location']);
            //Custom validation: check for part of our own url in the request, if not it's XXS!
            if(!strpos($return_url,  Url::base(true))){
                $return_url = Url::to(['/home']);
            }
        } else {
            $return_url = Url::to(['/home']);
        }     

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect($return_url);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Signup action for admin users
     *
     * @return Response|string
     */
    public function actionSignup()
    {
        
        throw new ForbiddenHttpException("You cannot signup for this site.");

        $model = new SignupForm();

        if($model->load(Yii::$app->request->post()) && $model->signup()){
            return $this->redirect('home');
        }

        return $this->render('signup', [
            'model' => $model,
        ]);

    }

    /**
     * Logout action for user.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        $url = Url::to(['/home']);
        return $this->redirect($url);
    }


    //** SIGNUP AND LOGIN ACTIONS FOR TEAMS ------------------------------- */

    /**
     * 
     * Login as team action (member)
     *
     * @return Response|string
     */
    public function actionLoginteam()
    {
        if(isset($_POST['location'])){
            $return_url = htmlspecialchars($_POST['location']);
            //Custom validation: check for part of our own url in the request, if not it's XXS!
            if(!strpos($return_url,  Url::base(true))){
                $return_url = Url::to(['/home']);
            }
        } else {
            $return_url = Url::to(['/home']);
        }     

        if(!Yii::$app->user->isGuest){
            $this->actionActiveround();
        }

        $model = new LoginTeamForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->actionActiveround();
        }

        return $this->render('loginteam', [
            'model' => $model,
        ]);
    }


    /**
     * Signup as team action (member)
     *
     * @return Response|string
     */
    public function actionSignupteam()
    {
        
        $model = new SignupTeamForm();

        if($model->load(Yii::$app->request->post()) && $model->signup()){
            $model = Round::getItemsByIndex(1);
            return $this->redirect(['round/form', 'slug' => $model->slug]);
        }

        return $this->render('signupteam', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action for team.
     *
     * @return Response
     */
    public function actionTeamlogout()
    {
        Yii::$app->team->logout();
        $url = Url::to(['/home']);
        return $this->redirect($url);
    }

    /**
     * 
     * Find active round for current team
     * 
     */
    public function actionActiveround()
    {
        $sessionUser = Yii::$app->user->identity;
        $lastRound = Record::find()->select('')->andWhere(['team_id' => $sessionUser->id])->innerJoinWith('round')->max('round.order_index');

        if(!$lastRound){ $lastRound = 0; }

        $round = Round::getItemsByIndex($lastRound + 1);

        if(!$round){
            //TO DO: Change this to "ending page" -> with a thank you message!
            return $this->redirect('end');
        }

        return $this->redirect(['round/form', 'slug' => $round->slug]);
    }


    /** CONTACT AND MAILSINGUP (LEAVE FOR NOW, PROBABLY DELETABLE) ----------------------- */

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params)) {    
            
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }


    /**
     * Signup for mailing contact list action.
     *
     * @return Response|string
     */
    public function actionMailsignup()
    {
        
        $model = new MailSignupForm();

        if($model->load(Yii::$app->request->post()) && $model->NewsletterSignup()){

            Yii::$app->session->setFlash('mailConfirmation');
        }
        
        return $this->render('mailsignup', [
            'model' => $model,
        ]);
        
    }
  

}

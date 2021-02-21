<?php

namespace app\modules\quizModule\controllers;

use Yii;
use app\modules\quizModule\models\QuizEvent;
use app\modules\quizModule\models\QuizEventSearch;
use app\modules\quizModule\models\team\SignupTeamForm;
use app\modules\quizModule\models\Record;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\imagine\Image;
use yii\data\Pagination;

/**
 * QuizEventController implements the CRUD actions for QuizEvent model.
 */
class QuizEventController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all QuizEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $itemsPerPage = 10;
        if(!isset($_GET['per-page'])){$_GET['per-page'] = $itemsPerPage;}

        $searchModel = new QuizEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Get total of models
        $countAll = $searchModel->all(Yii::$app->request->queryParams)->count();

        if($countAll > $itemsPerPage){
            $pages = new Pagination(['totalCount' => $countAll, 'PageSize' => $itemsPerPage]);
        } else {
            $pages = new Pagination(['totalCount' => $countAll, 'PageSize' => 0]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pages' => $pages
        ]);
    }

    /**
     * Displays a single QuizEvent model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);

        $pages = new Pagination(['totalCount' => count($model->teams)]);

        $teams = $model->getTeamsPaged($pages)->all();

        return $this->render('view', [
            'model' => $model,
            'teams' => $teams,
            'pages' => $pages,
        ]);
    }

    /**
     * Creates a new QuizEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QuizEvent();

        if ($model->load(Yii::$app->request->post()) ) {

            $model->uuid = $this->generateRandomString();

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QuizEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing QuizEvent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    //** SIGNUP AND LOGIN ACTIONS FOR TEAMS ------------------------------- */

    /**
     * 
     * Login as team action (member)
     *
     * @return Response|string
     */
    public function actionLoginteam($id)
    {

        $quizEvent = $this->findModel($id);

        //TODO: checken of team in deze quiz al ingeschreven is / meedoet!

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
            return $this->goHome();
        }

        if(!Yii::$app->team->isGuest){
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
    public function actionSignupteam($id)
    {

        $quizEvent = $this->findModel($id);
        
        if(!Yii::$app->user->isGuest){
            return $this->goHome();
        }

        if(!Yii::$app->team->isGuest){
            $this->actionActiveround();
        }

        $model = new SignupTeamForm();

        if($model->load(Yii::$app->request->post()) && $model->signup($id)){
            $round = $quizEvent->quiz->firstround;
            return $this->redirect(['round/form', 'slug' => $round->slug]);
            //Dit moet dus naar ronde 1 gaan!
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
        $sessionUser = Yii::$app->team->identity;
        $lastRound = Record::find()->select('')->andWhere(['team_id' => Yii::$app->team->id])->innerJoinWith('round')->max('round.order_index');

        if(!$lastRound){ $lastRound = 0; }

        $round = Round::getItemsByIndex($lastRound + 1);

        if(!$round){
            //TO DO: Change this to "ending page" -> with a thank you message!
            return $this->redirect('end');
        }

        return $this->redirect(['round/form', 'slug' => $round->slug]);
    }


    /**
     * Finds the QuizEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QuizEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QuizEvent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}

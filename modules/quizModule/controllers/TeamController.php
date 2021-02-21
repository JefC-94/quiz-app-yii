<?php

namespace app\modules\quizModule\controllers;

use Yii;
use app\modules\quizModule\models\team\Team;
use app\modules\quizModule\models\team\TeamSearch;
use app\modules\quizModule\models\Record;
use app\modules\quizModule\models\Question;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use \yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\components\AccessRule;

/**
 * TeamController implements the CRUD actions for Team model.
 */
class TeamController extends Controller
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
            /* 'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    if(Yii::$app->user->isGuest or Yii::$app->user->identity->isMember()){
                        return Yii::$app->getResponse()->redirect(['/home'],302);
                    } else {
                        throw new ForbiddenHttpException('You are not allowed to create, update or delete users.');
                    }
                },
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'members', 'edit', 'resetpassword'],
                        'allow' => false,
                        'roles' => ['member'],
                    ],
                    [
                        'actions' => ['index', 'view', 'edit', 'resetpassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['assess', 'create', 'update', 'delete', 'members'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ], */
        ];
    }

    /**
     * Lists all Team models except for members.
     * @return mixed
     */
    public function actionIndex()
    {
        $itemsPerPage = 10;
        if(!isset($_GET['per-page'])){ $_GET['per-page'] = $itemsPerPage; }

        $searchModel = new TeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Get count of models (filtered query)
        $count = $searchModel->search(Yii::$app->request->queryParams)->query->count();
    
        //Display the pagination if there are more than $itemsPerPage items;
        if($count > $itemsPerPage){
            $pages = new Pagination(['totalCount' => $count, 'pageSize' => $itemsPerPage]);
        } else {
            $pages = new Pagination(['totalCount' => $count, 'pageSize' => 0]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pages' => $pages,
        ]);
    }

    /**
     * Displays a single Team model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Team();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {

            $model->access_token = \Yii::$app->security->generateRandomString();
            $model->auth_key = \Yii::$app->security->generateRandomString();

            if($model->validate()){

                //return true;
                if($model->save()){
                    
                    return $this->redirect(['team/index']);    
                }
            }
                
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Team model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {

            if($model->validate()){
                
                if($model->save()){
                    return $this->redirect(['team/index']);
                }
            }   

            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionAssess($id, $rec_id, $correct)
    {
        
        $model = $this->findModel($id); 
        $record = Record::find()->where(['id' => $rec_id])->one();

        if($correct){
            if($record->correct !== 1){
                $record->correct = 1;
                $record->save();
                $model->score++;
                $model->save();
            }
        } 
        
        if(!$correct){
            if($record->correct === null){
                $record->correct = 0;
                $record->save();
            }
            if($record->correct !== 0){
                $record->correct = 0;
                $record->save();
                $model->score--;
                $model->save();
            }
        }

        return $correct;

    }


    /**
     * Deletes an existing Team model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * Finds the Team model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Team the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Team::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}

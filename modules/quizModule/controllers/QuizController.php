<?php

namespace app\modules\quizModule\controllers;

use Yii;
use app\modules\quizModule\models\Quiz;
use app\modules\quizModule\models\QuizSearch;
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
 * QuizController implements the CRUD actions for Quiz model.
 */
class QuizController extends Controller
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
     * Lists all Quiz models.
     * @return mixed
     */
    public function actionIndex()
    {

        $itemsPerPage = 10;
        if(!isset($_GET['per-page'])){$_GET['per-page'] = $itemsPerPage;}

        $searchModel = new QuizSearch();
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
            'pages' => $pages,
        ]);
    }

    /**
     * Displays a single Quiz model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($slug)
    {

        $model = $this->findModel($slug);

        $highest_index = count($model->rounds);

        $pages = new Pagination(['totalCount' => $highest_index]);

        $rounds = $model->getRoundsPaged($pages)->all();

        return $this->render('view', [
            'model' => $model,
            'highest_index' => $highest_index,
            'rounds' => $rounds,
            'pages' => $pages,
        ]);
    }

    /**
     * Creates a new Quiz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Quiz();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'slug' => $model->slug]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Quiz model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($slug)
    {
        $model = $this->findModel($slug);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'slug' => $model->slug]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Quiz model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($slug)
    {
        $this->findModel($slug)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Quiz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $slug
     * @return Quiz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($slug)
    {
        if (($model = Quiz::findOne(['slug' => $slug])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

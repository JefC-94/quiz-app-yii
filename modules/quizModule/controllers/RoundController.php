<?php

namespace app\modules\quizModule\controllers;

use Yii;
use app\modules\quizModule\models\Round;
use app\modules\quizModule\models\RoundSearch;
use app\modules\quizModule\models\Question;
use app\models\Record;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\imagine\Image;
use yii\data\Pagination;

/**
 * RoundController implements the CRUD actions for Round model.
 */
class RoundController extends Controller
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
                        throw new ForbiddenHttpException('You are not allowed to see this page.');
                    }
                },
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'viewquestion', 'viewsolution', 'moveitem', 'start', 'solutions', 'startquiz', 'previousround'],
                        'allow' => false,
                        'roles' => ['member'],
                    ],
                    [
                        'actions' => ['form'],
                        'allow' => true,
                        'roles' => ['member'],
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view', 'viewquestion', 'viewsolution', 'moveitem', 'start', 'solutions', 'startquiz', 'previousround'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ], */
        ];
    }

    /**
     * Lists all Round models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $itemsPerPage = 10;
        if(!isset($_GET['per-page'])){$_GET['per-page'] = $itemsPerPage;}

        $searchModel = new RoundSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Get total of models
        $countAll = $searchModel->all(Yii::$app->request->queryParams)->count();

        $highest_index = $searchModel->highestIndex();

        if($countAll > $itemsPerPage){
            $pages = new Pagination(['totalCount' => $countAll, 'PageSize' => $itemsPerPage]);
        } else {
            $pages = new Pagination(['totalCount' => $countAll, 'PageSize' => 0]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pages' => $pages,
            'highest_index' => $highest_index,
        ]);
    }

    /**
     * Displays a single Round model.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($slug)
    {
        
        $model = $this->findModel($slug);

        if($model->questions){
            $indexes = array_map(function($n){return $n->order_index;}, $model->questions);
            $highest_index = max($indexes);
        } else {
            $highest_index = 0;
        }
        
        return $this->render('view', [
            'model' => $model,
            'highest_index' => $highest_index,
        ]);
    }

    /**
     * Creates a new Round model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Round();

        $model->order_index = Round::find()->max('order_index') + 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'slug' => $model->slug]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Round model.
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
     * Deletes an existing Round model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($slug)
    {
        $model = $this->findModel($slug);
        
        //sort next articles by lowering their index
        $changeIndex = $model->order_index;

        $nextRound = Round::getItemsAboveIndex($changeIndex);

        foreach ($nextRound as $round){
            $new_index = $round->order_index - 1;
            $round->order_index = $new_index;
            $round->save();
        }


        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the first round, immediately starts this
     */
    public function actionStartquiz()
    {

        $model = Round::getItemsByIndex(1);

        return $this->render('start', [
            'model' => $model,
        ]);

    }
    
    /**
     * Finds round model with slug, starts this round
     */
    public function actionStart($slug)
    {
        $model = $this->findModel($slug);

        return $this->render('start', [
            'model' => $model,
        ]);
    }

    /**
     * Round finished, solutions time!
     */
    public function actionSolutions($slug)
    {
        $model = $this->findModel($slug);

        return $this->render('solutions', [
            'model' => $model,
        ]);
    }


    public function actionPreviousround($slug)
    {

        $model = $this->findModel($slug);

        if($model->order_index == 1){
            return $this->render('start', [
                'model' => $model,
            ]);
        }

        $prevRound = Round::getItemsByIndex($model->order_index - 1);

        return $this->actionViewsolution($prevRound->slug, count($prevRound->questions));

    }


    public function actionViewquestion($slug, $order_index)
    {

        $model = $this->findModel($slug);
        $highest_index = count($model->questions);

        if($order_index == 0){
            return $this->actionstart($slug);
        }

        if($order_index > $highest_index){
            return $this->actionSolutions($slug);
        }

        $question = $model->getQuestionByIndex($model->id, $order_index);
        
        return $this->render('question', [
            'model' => $model,
            'question' => $question,
            'solution' => false,
        ]);
    }


    public function actionViewsolution($slug, $order_index)
    {

        $model = $this->findModel($slug);
        $highest_index = count($model->questions);

        if($order_index == 0){
            return $this->actionViewquestion($slug, $highest_index);
        }

        if($order_index > $highest_index){

            $nextRound = Round::getItemsByIndex($model->order_index + 1);

            //quiz is afgelopen!!
            if(!$nextRound){
                return $this->redirect('../end');
            }

            return $this->actionStart($nextRound->slug);
        }

        $question = $model->getQuestionByIndex($model->id, $order_index);

        return $this->render('question', [
            'model' => $model,
            'question' => $question,
            'solution' => true,
        ]);

    }
    

    public function actionForm($slug){

        $model = $this->findModel($slug);

        $checkRec = Record::find()->andWhere(['round_id' => $model->id])->andWhere(['team_id' => Yii::$app->team->id])->one();

        if($checkRec){
            $this->redirect('home');
        }

        $records = [];

        for($i = 0; $i < count($model->questions) ; $i++){
            $rec = new Record();
            array_push($records, $rec);
        }

        if ($model->load(Yii::$app->request->post())) {
 
            $answers = $_POST['Round']['records'];
            
            foreach($answers as $index => $answer){
                $obj = new Record();
                $obj->team_id = Yii::$app->team->id;
                $obj->round_id = $model->id;
                $obj->order_index = $index+1;
                $obj->answer = $answer;
                $obj->save();
            }

            $nextRound = Round::getItemsByIndex($model->order_index+1);

            if(!$nextRound){
                return $this->redirect('../home');
            }

            return $this->redirect(['form', 'slug' => $nextRound->slug]);
        
        }

        return $this->render('form', [
            'model' => $model,
            'records' => $records,
        ]);
    }


    /**
     * Moves an item
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMoveitem($pos, $slug)
    {
            
        $model = $this->findModel($slug);

        if($pos === "down"){
            $newIndex = $model->order_index - 1;
            $nextItem = Round::getItemsByIndex($newIndex);
        }
        if($pos === "up"){
            $newIndex = $model->order_index + 1;
            $nextItem = Round::getItemsByIndex($newIndex);
        }
        if($pos === "down" or $pos === "up"){
            $nextItem->order_index = $model->order_index;
            $nextItem->save();
        }

        if($pos === "first"){
            $newIndex = 1;
            $nextItems = Round::getItemsBelowIndex($model->order_index);
            foreach ($nextItems as $item){
                $new_index = $item->order_index + 1;
                $item->order_index = $new_index;
                $item->save();
            }
        }
        if($pos === "last"){
            $newIndex = Round::find()->max('order_index');
            $nextItems = Round::getItemsAboveIndex($model->order_index);
            foreach ($nextItems as $item){
                $new_index = $item->order_index - 1;
                $item->order_index = $new_index;
                $item->save();
            }
        }

        $model->order_index = $newIndex;

        if($model->save()){
            //return to index with right pagination!!
            return $this->redirect(['round/index']);
        }

    }

    /**
     * Finds the Round model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $slug
     * @return Round the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($slug)
    {
        if (($model = Round::findOne(['slug' => $slug])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

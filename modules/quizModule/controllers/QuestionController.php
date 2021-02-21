<?php

namespace app\modules\quizModule\controllers;

use Yii;
use app\modules\quizModule\models\Question;
use app\modules\quizModule\models\Round;
use app\modules\quizModule\models\QuestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\imagine\Image;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
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
            'access' => [
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => false,
                        'roles' => ['member'],
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view', 'moveitem'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($slug, $order_index)
    {
        $round = Round::getRound($slug);

        $highest_index = count($round->questions);

        if($order_index == 0){
            return $this->render('/round/start', [
                'model' => $round,
            ]);
        }

        //CHANGE THIS: ANTWOORDEN OVERLOPEN (MSS ALLEMAAL OP ééN PAGINA GEWOON?)
        //DAARNA: moet zoeken naar volgende ronde (met index + 1) en deze starten
        if($order_index > $highest_index){

            $nextRound = Round::getItemsByIndex($round->order_index + 1);

            return $this->render('/round/start', [
                'model' => $nextRound,
            ]);
        }

        //FIND QUESTION:
        $allQuestions = $round->questions;

        foreach($allQuestions as $question){
            if($order_index == $question->order_index){
                $model = $question;
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($slug)
    {
        
        $round = Round::getRound($slug);
        $questionCount = $round->getQuestions()->count();
        
        $model = new Question();
        $model->scenario = "create";

        $model->round_id = $round->id;
        $model->order_index = $questionCount + 1;

        if($model->load(Yii::$app->request->post())) {

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if($model->validate()){

                $model->image = 'uploads/questions/' . $model->round_id . '-' . $model->imageFile->baseName . Yii::$app->params['image-extension'];
            
                if($model->save()){

                    $thumbnail = Image::thumbnail($model->imageFile->tempName, 100000, 100000)->save('uploads/questions/' . $model->round_id . '-' . $model->imageFile->baseName . Yii::$app->params['image-extension'], ['quality' => 90]);

                    return $this->redirect(['round/view', 'slug' => $slug]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($slug, $id)
    {

        $round = Round::getRound($slug);

        $model = $this->findModel($id);
        $model->scenario = "update";

        $originalImage = $model->image;
        
        if ($model->load(Yii::$app->request->post())) {

            //CHECK FOR NEW IMAGE
            $uploadedFile = UploadedFile::getInstance($model, 'imageFile');

            if(isset($uploadedFile)){
                $model->imageFile = $uploadedFile;
                $model->image = 'uploads/questions/' . $model->round_id . '-' . $model->imageFile->baseName . Yii::$app->params['image-extension'];
            }

            if($model->save()){

                //CHECK IF NEW IMAGE -> RESIZE & CONVERT THE IMAGE + SAVE
                if(isset($uploadedFile)){
                    $model->imageFile = $uploadedFile;

                    if(file_exists($originalImage)){
                        unlink(\Yii::getAlias('@webroot/') . $originalImage);
                    }

                    $thumbnail = Image::thumbnail($model->imageFile->tempName, 100000, 100000)->save('uploads/questions/' . $model->round_id . '-' . $model->imageFile->baseName . Yii::$app->params['image-extension'], ['quality' => 90]);
                    
                }

                return $this->redirect(['round/view', 'slug' => $slug]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Question model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($slug, $id)
    {

        $round = Round::getRound($slug);

        $model= $this->findModel($id);

        //delete featured image
        if (file_exists($model->image)){
            unlink(\Yii::getAlias('@webroot/') . $model->image);
        }

        //sort next articles by lowering their index
        $changeIndex = $model->order_index;

        $nextQuestion = Question::getItemsAboveIndex($changeIndex, $round->id);

        foreach ($nextQuestion as $question){
            $new_index = $question->order_index - 1;
            $question->order_index = $new_index;
            $question->save();
        }

        $model->delete();



        return $this->redirect(['round/view', 'slug' => $slug]);
    }


    /**
     * Moves an item
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMoveitem($pos, $id)
    {
            
        $model = $this->findModel($id);
        $round_id = $model->round_id;

        if($pos === "down"){
            $newIndex = $model->order_index - 1;
            $nextItem = Question::getItemsByIndex($newIndex, $round_id);
        }
        if($pos === "up"){
            $newIndex = $model->order_index + 1;
            $nextItem = Question::getItemsByIndex($newIndex, $round_id);
        }
        if($pos === "down" or $pos === "up"){
            $nextItem->order_index = $model->order_index;
            $nextItem->save();
        }

        if($pos === "first"){
            $newIndex = 1;
            $nextItems = Question::getItemsBelowIndex($model->order_index, $round_id);
            foreach ($nextItems as $item){
                $new_index = $item->order_index + 1;
                $item->order_index = $new_index;
                $item->save();
            }
        }
        if($pos === "last"){
            $newIndex = Question::find()->max('order_index');
            $nextItems = Question::getItemsAboveIndex($model->order_index, $round_id);
            foreach ($nextItems as $item){
                $new_index = $item->order_index - 1;
                $item->order_index = $new_index;
                $item->save();
            }
        }

        $model->order_index = $newIndex;

        if($model->save()){
            //return to index with right pagination!!
            return $this->redirect(['round/view', 'slug' => $model->round->slug]);
        }

    }


    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

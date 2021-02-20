<?php

namespace app\controllers;

use Yii;
use app\models\Profile;
use app\models\ProfileSearch;
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
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
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
                'class' => \yii\filters\AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    if(Yii::$app->user->isGuest or Yii::$app->user->identity->isMember()){
                        return Yii::$app->getResponse()->redirect(['/home'],302);
                    } else {
                        throw new ForbiddenHttpException('You are not allowed to access this page');
                    }
                },
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view'],
                        'allow' => false,
                        'roles' => ['member'],
                    ],
                    [
                        'actions' => ['delete', 'create', 'update', 'view', 'index'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Profile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $itemsPerPage = 10;
        if(!isset($_GET['per-page'])){$_GET['per-page'] = $itemsPerPage;}

        $searchModel = new ProfileSearch();
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
     * Displays a single Profile model.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($slug)
    {
        return $this->render('view', [
            'model' => $this->findModel($slug),
        ]);
    }

    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($user_id)
    {
        
        $sessionUser = Yii::$app->user->identity;
        if($sessionUser->id != $user_id){
            throw new ForbiddenHttpException("you do not have permission");
        }

        $query = Profile::find()
        ->where(['user_id' => $user_id])
        ->all();

        if(!empty($query)){
            Yii::$app->session->setFlash('errorCreate');
            return $this->redirect('index');
        }
        
        $model = new Profile();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = $user_id;
            $model->name = $_POST['Profile']['firstname'] . $_POST['Profile']['lastname'];

            //create instance of the imagefile on the server
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if($model->validate()){

                //save the path in the db
                $model->image = 'uploads/profile/'.$model->imageFile->baseName . Yii::$app->params['image-extension'];

                if($model->save()){

                //reduce save the imagefile on the server
                $img_size = 200;
                $thumbnail = Image::thumbnail($model->imageFile->tempName, $img_size, $img_size);
                $size = $thumbnail->getSize();
                if($size->getWidth() < $img_size or $size->getHeight() < $img_size) {
                    $white = Image::getImagine()->create(new Box($img_size, $img_size));
                    $thumbnail = $white->paste($thumbnail, new Point($img_size / 2 - $size->getWidth() / 2, $img_size / 2 - $size->getHeight() / 2));
                }
                $thumbnail->save('uploads/profile/'.$model->imageFile->baseName . Yii::$app->params['image-extension'], ['quality' => 90]);

                return $this->redirect(['view', 'slug' => $model->slug]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($slug)
    {

        $model = $this->findModel($slug);

        $sessionUser = Yii::$app->user->identity;
        if($sessionUser->id != $model->user_id){
            throw new ForbiddenHttpException("you do not have permission");
        }

        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {

            $model->name = $_POST['Profile']['firstname'] . $_POST['Profile']['lastname'];

            $originalFile = $model->imageFile;
            $uploadedFile = UploadedFile::getInstance($model, 'imageFile');

            if(isset($uploadedFile)){
                $model->imageFile = $uploadedFile;
                $model->image = 'uploads/profile/'.$model->imageFile->baseName . Yii::$app->params['image-extension'];
            }

            if($model->validate()){

                if($model->save()){;

                    if(isset($uploadedFile)){
                        //resize the image to a square of 200x200 (from center) + convert to jpg + save the imagefile on the server +                   
                        $img_size = 200;
                        $thumbnail = Image::thumbnail($model->imageFile->tempName, $img_size, $img_size);
                        $size = $thumbnail->getSize();
                        if($size->getWidth() < $img_size or $size->getHeight() < $img_size) {
                            $white = Image::getImagine()->create(new Box($img_size, $img_size));
                            $thumbnail = $white->paste($thumbnail, new Point($img_size / 2 - $size->getWidth() / 2, $img_size / 2 - $size->getHeight() / 2));
                        }
                        $thumbnail->save('uploads/profile/'.$model->imageFile->baseName . Yii::$app->params['image-extension'], ['quality' => 90]);

                    }

                return $this->redirect(['view', 'slug' => $model->slug]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Profile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($slug)
    {

        $model = $this->findModel($slug);
        
        if($model->user){
            throw new ForbiddenHttpException('This profile is linked to an existing user and cannot be deleted.');
        }
        
        if(file_exists($model->image)){
            unlink(\Yii::getAlias('@webroot/') . $model->image);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $slug
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($slug)
    {
        if (($model = Profile::findOne(['slug' => $slug])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

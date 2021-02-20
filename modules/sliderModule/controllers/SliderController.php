<?php

namespace app\modules\sliderModule\controllers;

use Yii;
use app\modules\sliderModule\models\Slider;
use app\modules\sliderModule\models\SliderSearch;
use app\modules\sliderModule\models\Slide;
use app\modules\sliderModule\models\Sl_Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\imagine\Image;
use yii\data\Pagination;

/**
 * SliderController implements the CRUD actions for Slider model.
 */
class SliderController extends Controller
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
                        throw new ForbiddenHttpException('You are not allowed to create or edit a slider. Please contact the administrator to give you "editor" clearance.');
                    }
                },
                'rules' => [
                    [
                        'actions' => ['index', 'edit', 'create', 'update', 'delete'],
                        'allow' => false,
                        'roles' => ['member'],
                    ],
                    [
                        'actions' => ['view', 'singleview', 'quadview', 'duoview'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['index', 'adminview'],
                        'allow' => true,
                        'roles' => ['editor', 'author', 'admin'],
                    ],
                    [
                        'actions' => ['update', 'create', 'adminview', 'delete'],
                        'allow' => true,
                        'roles' => ['editor', 'admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Slider models.
     * @return mixed
     */
    public function actionIndex()
    {
        $itemsPerPage = 10;
        if(!isset($_GET['per-page'])){$_GET['per-page'] = $itemsPerPage;}

        $searchModel = new SliderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Get total of models
        $countAll = $searchModel->search(Yii::$app->request->queryParams)->query->count();

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
     * Displays a single Slider model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAdminview($slug)
    {
        return $this->render('view/adminview', [
            'model' => $this->findModel($slug),
        ]);
    }

    /**
     * Displays a single Slider model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSingleview($slug)
    {
        return $this->renderAjax('view/singleview', [
            'model' => $this->findModel($slug),
        ]);
    }

    /**
     * Displays a single Slider model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDuoview($slug)
    {
        return $this->renderAjax('view/duoview', [
            'model' => $this->findModel($slug),
        ]);
    }

    /**
     * Displays a single Slider model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionQuadview($slug)
    {
        return $this->renderAjax('view/quadview', [
            'model' => $this->findModel($slug),
        ]);
    }

    /**
     * Creates a new Slider model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Slider();

        if ($model->load(Yii::$app->request->post())) {

            $model->aspect_ratio = $_POST['Slider']['width'] / $_POST['Slider']['height'];

            if($model->save()){
                return $this->redirect(['adminview', 'slug' => $model->slug]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Slider model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($slug)
    {
        $model = $this->findModel($slug);
        
        if ($model->load(Yii::$app->request->post())) {

            $model->aspect_ratio = $_POST['Slider']['width'] / $_POST['Slider']['height'];

            if($model->save()){
                return $this->redirect(['adminview', 'slug' => $model->slug]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Slider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($slug)
    {
        $model = $this->findModel($slug);

        $slides = $model->slides;

            foreach ($slides as $slide){
            $sl_image = Sl_Image::GetSl_Image($slide->image_id);
            if (file_exists($sl_image->imagepath)){
                unlink(\Yii::getAlias('@webroot/') . $sl_image->imagepath);
            }
            $sl_image->delete();
        }

        $model->delete();
        
        return true;
    }

    /**
     * Finds the Slider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $slug
     * @return Slider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($slug)
    {
        if (($model = Slider::findOne(['slug' => $slug])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

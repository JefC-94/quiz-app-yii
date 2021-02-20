<?php

namespace app\modules\sliderModule\controllers;

use Yii;
use app\modules\sliderModule\models\Slider;
use app\modules\sliderModule\models\Slide;
use app\modules\sliderModule\models\SlideSearch;
use app\modules\sliderModule\models\Sl_Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\imagine\Image;


/**
 * SlideController implements the CRUD actions for Slide model.
 */
class SlideController extends Controller
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
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['editor', 'author', 'admin'],
                    ],
                    [
                        'actions' => ['update', 'create', 'delete', 'moveslideup', 'moveslidedown', 'moveslidefirst', 'moveslidelast'],
                        'allow' => true,
                        'roles' => ['editor', 'admin'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Slide models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SlideSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Slide model.
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
     * Creates a new Slide model.
     * If creation is successful, the browser will be redirected to the 'view' page of the slider.
     * @return mixed
     */
    public function actionCreate($slug)
    {

        $slider = Slider::getSlider($slug);
        $slideCount = $slider->getCountSlides();

        $model = new Slide();

        $model->min_width = $slider->getMinWidth();
        $model->min_height = $slider->getMinHeight();
        $model->slug = $slider->slug;

        $model->scenario = "create";

        //image nog instellen!!
        if ($model->load(Yii::$app->request->post())) {

            $model->slider_id = $slider->id;

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            $sl_image = new Sl_Image;

            //save the path of the new resized image in the db -> we will later convert this images to jpg so already change this here too
            $sl_image->imagepath = 'uploads/slider/'  . $slider->slug . '-' . $model->imageFile->baseName . Yii::$app->params['image-extension']; 

            if($sl_image->save()){

                $model->image_id = $sl_image->id;

                if($slideCount == 0){
                    $model->slide_index = 1;
                } else {
                    $model->slide_index = $slideCount+1;
                }

                $thumbnail = Image::thumbnail($model->imageFile->tempName, 100000, 100000)->save('uploads/slider/' . $slider->slug . '-' . $model->imageFile->baseName . Yii::$app->params['image-extension'], ['quality' => 90]);

                if($model->save()){
                    return $this->redirect(['slider/adminview', 'slug' => $slider->slug]);
                }

            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Slide model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($slug, $id)
    {
        $slider = Slider::getSlider($slug);

        $model = $this->findModel($id);

        $model->min_width = $slider->getMinWidth();
        $model->min_height = $slider->getMinHeight();
        
        $model->scenario = "update";

        if($model->load(Yii::$app->request->post())) {

            $uploadedFile = UploadedFile::getInstance($model, 'imageFile');
        
            if(isset($uploadedFile)){         
                
                //delete previous version of the image from web/uploads/slider directory
                $originalImage = Sl_Image::getSl_Image($model->image_id);
                if (file_exists($originalImage->imagepath)){
                    unlink(\Yii::getAlias('@webroot/') . $originalImage->imagepath);
                }

                $model->imageFile = $uploadedFile;
                
                $sl_image = new Sl_Image;
                //save the path of the new resized image in the db -> we will later convert this images to jpg so already change this here too
                $sl_image->imagepath = 'uploads/slider/' . $slider->slug . '-' . $model->imageFile->baseName . Yii::$app->params['image-extension']; 

                if($sl_image->save()){
                    $model->image_id = $sl_image->id;
                    $thumbnail = Image::thumbnail($model->imageFile->tempName, 100000, 100000)->save('uploads/slider/'. $slider->slug . '-' . $model->imageFile->baseName . Yii::$app->params['image-extension'], ['quality' => 90]);
                }
            }

            if($model->save()){

                //delete the record from the previous image from the sl_images database
                if(isset($uploadedFile)){                    
                    $originalImage->delete();
                }

                return $this->redirect(['slider/adminview', 'slug' => $slider->slug]);
            }
            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Slide model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($slug, $id)
    {

        $slider = Slider::getSlider($slug);

        $model = $this->findModel($id);

        if($slider->countSlides == 1){
            Yii::$app->session->setFlash('errorDelete');
            return $this->redirect(['slider/adminview', 'slug' => $slug]);
        }

        $sl_image = Sl_Image::GetSl_Image($model->image_id);
        if (file_exists($sl_image->imagepath)){
            unlink(\Yii::getAlias('@webroot/') . $sl_image->imagepath);
        }
        
        $changeIndex = $model->slide_index;

        $nextSlides = Slide::getSlidesAboveIndex($slider->id, $changeIndex);

        foreach ($nextSlides as $slide){
            $new_index = $slide->slide_index - 1;
            $slide->slide_index = $new_index;
            $slide->save();
        }
        
        $sl_image->delete();
        $model->delete();

        return $this->redirect(['slider/adminview', 'slug' => $slug]);
    }


    /**
     * Moves a slide on down or on up
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $slug, $slide_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMoveslideup($slug, $id)
    {
        
        $slider = Slider::getSlider($slug);

        $model = $this->findModel($id);

        $changeIndex = $model->slide_index + 1;

        $nextSlide = Slide::getSlideByIndex($slider->id, $changeIndex);

        $nextSlide->slide_index = $model->slide_index;
        $model->slide_index = $changeIndex;

        if($nextSlide->save() && $model->save()){
            return $this->redirect(['slider/adminview', 'slug' => $slug]);
        }

    }


    /**
     * Moves a slide on down or on up
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $slug, $slide_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMoveslidedown($slug, $id)
    {
        
        $slider = Slider::getSlider($slug);

        $model = $this->findModel($id);

        $changeIndex = $model->slide_index - 1;

        $nextSlide = Slide::getSlideByIndex($slider->id, $changeIndex);

        $nextSlide->slide_index = $model->slide_index;
        $model->slide_index = $changeIndex;
        
        if($nextSlide->save() && $model->save()){
            return $this->redirect(['slider/adminview', 'slug' => $slug]);
        }

    }

    /**
     * Moves a slide all the way to the first position
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $slug, $slide_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMoveslidefirst($slug, $id)
    {
        
        $slider = Slider::getSlider($slug);

        $model = $this->findModel($id);

        $changeIndex = $model->slide_index;

        $nextSlides = Slide::getSlidesBelowIndex($slider->id, $changeIndex);

        foreach ($nextSlides as $slide){
            $new_index = $slide->slide_index + 1;
            $slide->slide_index = $new_index;
            $slide->save();
        }

        $model->slide_index = 1;
        
        if($model->save()){
            return $this->redirect(['slider/adminview', 'slug' => $slug]);
        }
    }

    /**
     * Moves a slide all the way to the last position
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $slug, $slide_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMoveslidelast($slug, $id)
    {
        
        $slider = Slider::getSlider($slug);

        $model = $this->findModel($id);

        $changeIndex = $model->slide_index;

        $nextSlides = Slide::getSlidesAboveIndex($slider->id, $changeIndex);

        foreach ($nextSlides as $slide){
            $new_index = $slide->slide_index - 1;
            $slide->slide_index = $new_index;
            $slide->save();
        }

        $model->slide_index = $slider->getCountSlides();

        if($model->save()){
            return $this->redirect(['slider/adminview', 'slug' => $slug]);
        }

    }


    /**
     * Finds the Slide model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Slide the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Slide::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

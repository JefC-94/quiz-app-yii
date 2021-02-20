<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\MemberSearch;
use app\models\UserRole;
use app\models\Role;
use app\models\Profile;
use app\models\Record;
use app\models\Question;
use app\models\userforms\EditForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use \yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\components\AccessRule;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
            ],
        ];
    }

    /**
     * Lists all User models except for members.
     * @return mixed
     */
    public function actionIndex()
    {
        $itemsPerPage = 10;
        if(!isset($_GET['per-page'])){ $_GET['per-page'] = $itemsPerPage; }

        $searchModel = new UserSearch();
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
     * Lists all Member models.
     * @return mixed
     */
    public function actionMembers()
    {
        
        $itemsPerPage = 4;
        if(!isset($_GET['per-page'])){ $_GET['per-page'] = $itemsPerPage; }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->member(Yii::$app->request->queryParams);

        //Get count of models (filtered query)
        $count = $searchModel->member(Yii::$app->request->queryParams)->query->count();

        //Display the pagination if there are more than $itemsPerPage items;
        if($count > $itemsPerPage){
            $pages = new Pagination(['totalCount' => $count, 'pageSize' => $itemsPerPage]);
        } else {
            $pages = new Pagination(['totalCount' => $count, 'pageSize' => 0]);
        }

        return $this->render('members', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pages' => $pages,
        ]);
    }

    /**
     * Displays a single User model.
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

        $model = new User();
        $model->scenario = 'create';
        
        $model->selectedRoles = [];

        if ($model->load(Yii::$app->request->post())) {

            $model->password = \Yii::$app->security->generatePasswordHash($_POST['User']['password']);
            $model->access_token = \Yii::$app->security->generateRandomString();
            $model->auth_key = \Yii::$app->security->generateRandomString();

            if($model->validate()){

                //return true;
                if($model->save()){
                    foreach($_POST['User']['selectedRoles'] as $role_id){
                        $user_role = new UserRole;
                        $user_role->user_id = $model->id;
                        $user_role->role_id = $role_id;
                        if( !$user_role->save()) print_r($user_role->errors);
                    }

                    /* Send an e-mail to the new user with his password */
                    Yii::$app->mailer->compose()
                        ->setTo($model->email)
                        ->setFrom(Yii::$app->params["adminEmail"])
                        ->setSubject('You have been made user of ' . Url::base(true))
                        ->setTextBody(
                            'Dear,<br>you have been made user of '. Url::base(true) .'.<br>In this mail you will find your login credentials:<br>
                            username:' . $model->username . '<br>
                            password:' . $_POST['User']['password'] .'<br>
                            <div style="margin-top:2em;">You can login as a user on <a href="127.0.0.1:8080/yii-base/web/site/login">this link</a></div>
                            ')
                        ->send();

                    return $this->redirect(['user/index']);    
                    //User moet voor zichzelf een profiel aanmaken. Indien de admin dit doet, activeer deze code:
                    //return $this->redirect(['profile/create', 'user_id' => $model->id]);
                }
            }
                
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => Role::getRoles(),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $model->scenario = 'update';

        $model->selectedRoles = [];

        foreach($model->userRole as $user_role){
            array_push($model->selectedRoles, $user_role->role_id);
        }

        //members will always be members, no change in roles possible
        if($model->isMember()){
            throw new ForbiddenHttpException();
        }

        if ($model->load(Yii::$app->request->post())) {

            if($model->validate()){
                
                if($model->save()){
                    //verwijder vorige selectie
                    $this->deleteRoles($model->id);
                    //voeg nieuwe selectie toe
                    foreach($_POST['User']['selectedRoles'] as $role_id){
                        $user_role = new UserRole;
                        $user_role->user_id = $model->id;
                        $user_role->role_id = $role_id;
                        if( !$user_role->save()) print_r($user_role->errors);
                    }
                }
            }   

            return $this->redirect(['user/index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    
    /**
     * Update own username, password method
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {

        $model = new EditForm();

        $model->user = $this->findModel($id);
        
        $sessionUser = Yii::$app->user->identity;
        if($sessionUser->id !== $model->user->id){throw new ForbiddenHttpException("you do not have permission");}

        $model->username = $model->user->username;
        $model->email = $model->user->email;
        $model->password = $model->user->password;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            if ($model->edit($id)){
                return $this->redirect(['user/index']);
            }
        }

        return $this->render('edit', [
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
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * 
     * Delete the records for this user in the user_role table
     * 
     */
    public static function deleteRoles($id)
    {
        $query = UserRole::find()
            ->where(['user_id' => $id])
            ->all();

        foreach($query as $item){
            $item->delete();
        }
    }


}

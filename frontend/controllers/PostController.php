<?php

namespace frontend\controllers;

use Yii;
use common\models\Post;
use common\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Tag;
use common\models\Comment;
use yii\web\User;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public $added = 0; //代表还没有新的评论
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
            //设置页面缓存
//            'pageCache' => [
//                'class'=>'yii\filters\PageCache',//使用的类
//                'only'=>['index'],                 //只对index页面起作用
//                'duration'=>600,                 //过期时间
//                'variations'=>[
//                    Yii::$app->request->get('page'), //保证分页数据传输过来
//                    Yii::$app->request->get('PostSearch'), //保证分页数据传输过来
//                ],
//                'dependency'=>[
//                    'class'=>'yii\caching\DbDependency', //设置依赖
//                    'sql'=>'select count(id) from post', //依赖的sql，此句表示当文章数量有更新是缓存就重新生成
//                ],
//            ],
            'pageCache'=>[
                'class'=>'yii\filters\PageCache',
                'only'=>['index'],
                'duration'=>600,
                'variations'=>[
                    Yii::$app->request->get('page'),
                    Yii::$app->request->get('PostSearch'),
                ],
                'dependency'=>[
                    'class'=>'yii\caching\DbDependency',
                    'sql'=>'select count(id) from post',
                ],
            ],
            //设置http缓存
//            'httpCache' => [
//                'class' => 'yii\filters\HttpCache',
//                'only' => ['detail'],
//                'lastModified' => function ( $action, $params ) {
//                    $q = new \yii\db\Query();
//                    return $q->from('post')->max('update_time');
//                },
//
//                'cacheControlHeader' => 'public, max-age=600',
//            ],
        ];
    }

    public function beforeAction($action)
    {
         //echo "<script>alert(1111);</script>";
         return true;
    }
    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $tags=Tag::findTagWeights();
        //$recentComments=Comment::findRecentComments();

        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tags'=>$tags,
            //'recentComments'=>$recentComments,
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
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
     * Deletes an existing Post model.
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

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDetail($id){
        //1准备数据模型
        $model = $this->findModel($id);
        $tags = Tag::findTagWeights();
        $recentComments = '';

        $userMe = \common\models\User::findOne(Yii::$app->user->id);
        if(!$userMe){
            return $this->redirect(Yii::$app->urlManager->createUrl(['/site/login']));
        }
        $commentModel = new Comment();
        $commentModel->email = $userMe->email;
        $commentModel->userid = $userMe->id;
        //当评论提交时处理评论
        //p(Yii::$app->request->post());die;
        if($commentModel->load(Yii::$app->request->post())){
            $commentModel->status = 1;
            $commentModel->post_id = $id;
            if($commentModel->save()){
                $this->added = 1;
            }
        }
        return $this->render('detail',[
            'model'=>$model,
            'tags'=>$tags,
            'recentComment'=>$recentComments,
            'commentModel'=>$commentModel,
            'added' => $this->added,
        ]);
    }















}

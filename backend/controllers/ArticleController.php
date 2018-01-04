<?php
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

/**
 * 文章管理
 * Class ArticleController
 * @package backend\controllers
 */
class ArticleController extends Controller{
    /**
     * 显示文章列表
     */
    public function actionIndex(){
        $rows = Article::find()->where(['>=','status','0'])->all();
        return $this->render('index',['rows'=>$rows]);
    }

    /**
     * 添加文章
     */
    public function actionAdd(){
        //文章模型
        $model = new Article();
        //>>文章详情模型
        $article_detail = new ArticleDetail();
        $request = new Request();
        //>>查询分类id和名字(正常)
        $db = \Yii::$app->db;
        $sql = 'SELECT * FROM `article_category` where status>0';
        $articlecategor = $db->createCommand($sql)->queryAll();
        $articlecategor_id = ArrayHelper::map($articlecategor,'id','name');
        //>>提交
        if ($request->isPost){
            //>>验证文章
            $model->load($request->post());
            //>>验证文章详情
            $article_detail->load($request->post());
            if ($model->validate()){
                if ($article_detail->validate()){
                    //>>添加时间
                    $model->create_time=time();
                    //>>添加到两张表
                    $model->save();
                    //>>关联id
                    $article_detail->id = $model->id;
                    $article_detail->save();
                    //>>提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    //>>跳转
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('add',['model'=>$model,'articlecategor_id'=>$articlecategor_id,'article_detail'=>$article_detail]);
    }
    /**
     * UEditor 富文本编辑框
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }


    /**
     * 逻辑删除文章
     */
    public function actionDelete(){
        header("Content-Type:text/json; charset=UTF-8");
        $id = $_POST['id'];
        $model = Article::findOne($id);
        $model->status = '-1';
        if($model->save()){
            echo 1;
        }else{
            echo 0;
        };
    }
    /**
     * 文章修改
     */
    public function actionEdit($id){
        //文章模型
        $model =Article::findOne($id);
        //>>文章详情模型
        $article_detail = ArticleDetail::findOne($id);
        //>>提交表单模型
        $request = new Request();
        //>>查询分类id和名字(正常)
        $db = \Yii::$app->db;
        $sql = 'SELECT * FROM `article_category` where status>0';
        $articlecategor = $db->createCommand($sql)->queryAll();
        $articlecategor_id = ArrayHelper::map($articlecategor,'id','name');

        //>>提交
        if ($request->isPost){
            //>>验证文章
            $model->load($request->post());
            //>>验证文章详情
            $article_detail->load($request->post());
            if ($model->validate()){
                if ($article_detail->validate()){
                    //>>添加时间
                    $model->create_time=time();
                    //>>添加到两张表
                    $model->save();
                    //>>关联id
                    $article_detail->id = $model->id;
                    $article_detail->save();
                    //>>提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    //>>跳转
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('add',['model'=>$model,'articlecategor_id'=>$articlecategor_id,'article_detail'=>$article_detail]);
    }
    public function behaviors(){
        return[
            'time'=>[
                'class'=>RbacFilter::className(),
                'except'=>['upload'],
            ],
        ];
    }
}

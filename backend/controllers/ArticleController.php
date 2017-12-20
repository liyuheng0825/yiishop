<?php
namespace backend\controllers;
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
        $rows = Article::find()->all();
        return $this->render('index',['rows'=>$rows]);
    }

    /**
     * 添加文章
     */
    public function actionAdd(){
        $model = new Article();
        $request = new Request();
        //>>查询分类id和名字(正常)
        $db = \Yii::$app->db;
        $sql = 'SELECT * FROM `article_category` where status>0';
        $articlecategor = $db->createCommand($sql)->queryAll();
        $articlecategor_id = ArrayHelper::map($articlecategor,'id','name');
        //>>文章详情
        $article_detail = new ArticleDetail();
        //>>提交
        if ($request->isPost){

        }
        return $this->render('add',['model'=>$model,'articlecategor_id'=>$articlecategor_id,'article_detail'=>$article_detail]);
    }
}

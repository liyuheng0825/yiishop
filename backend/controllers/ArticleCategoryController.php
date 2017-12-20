<?php
namespace backend\controllers;
use backend\models\ArticleCategory;
use backend\models\Brand;
use yii\web\Controller;
use yii\web\Request;

/**
 * 文章分类管理
 * Class ArticleCategoryController
 * @package backend\controllers
 */
class ArticleCategoryController extends Controller{
    /**
     * 文章分类列表
     */
    public function actionIndex(){
        $db = \Yii::$app->db;
        $sql = 'SELECT * FROM `article_category` where status>=0';
        $rows = $db->createCommand($sql)->queryAll();

        return $this->render('index',['rows'=>$rows]);
    }
    /**
     * 文章分类添加
     */
    public function actionAdd(){
        //>>所有请求模型
        $request = new Request();
        //>>banrd模型
        $model = new ArticleCategory();
        if ($request->isPost){
            $model->load($request->post());
            //>>验证成功
            if ($model->validate()){
                //>>执行添加
                $model->save();
                //>>提示
                \Yii::$app->session->setFlash('success','添加成功');
                //>>跳转
                return $this->redirect(['index']);
            }else{
                //>>打印错误
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 文章分类修改
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
        //>>所有请求模型
        $request = new Request();
        //>>banrd模型
        $model = ArticleCategory::findOne($id);
        if ($request->isPost){
            $model->load($request->post());
            //>>验证成功
            if ($model->validate()){
                //>>执行添加
                $model->save();
                //>>提示
                \Yii::$app->session->setFlash('success','添加成功');
                //>>跳转
                return $this->redirect(['index']);
            }else{
                //>>打印错误
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 文章分类删除
     */
    public function actionDelete(){
        header("Content-Type:text/json; charset=UTF-8");
        $id = $_POST['id'];
        $model = ArticleCategory::findOne($id);
        $model->status = '-1';
        if($model->save()){
            echo 1;
        }else{
            echo 0;
        };
    }
}

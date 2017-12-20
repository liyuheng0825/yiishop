<?php
namespace backend\controllers;
use backend\models\Brand;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

/**
 * 品牌控制器
 * Class BrandController
 * @package frontend\controllers
 */
class BrandController extends Controller{
    /**
     * 显示品牌列表
     */
    public function actionIndex(){
        $db = \Yii::$app->db;
        $sql = 'SELECT * FROM `brand` where status>=0';
        $rows = $db->createCommand($sql)->queryAll();

        return $this->render('index',['rows'=>$rows]);
    }
    /**
     * 添加品牌
     */
    public function actionAdd(){
        //>>所有请求模型
        $request = new Request();
        //>>banrd模型
        $model = new Brand();
        if ($request->isPost){
            $model->load($request->post());
            //>>图片处理
            $model->logo = UploadedFile::getInstance($model,'logo');
            //>>验证成功
            if ($model->validate()){
                //>>图片路径地址拼接
                $file = '/upload/brand/'.uniqid().'.'.$model->logo->extension;
                if($model->logo->saveAs(\yii::getAlias('@webroot').$file)){
                    //>>文件上传成功
                    $model->logo = $file;
                }
                //>>执行添加
                $model->save(false);
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
     * 修改品牌
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
       $request =  new Request();
       $model = Brand::findOne($id);

        if ($request->isPost){
            $model->load($request->post());
            //>>图片处理
            $model->logo = UploadedFile::getInstance($model,'logo');
            //>>验证成功
            if ($model->validate()){
                //>>图片路径地址拼接
                $file = '/upload/brand/'.uniqid().'.'.$model->logo->extension;
                if($model->logo->saveAs(\yii::getAlias('@webroot').$file)){
                    //>>文件上传成功
                    $model->logo = $file;
                }
                //>>执行添加
                $model->save(false);
                //>>提示
                \Yii::$app->session->setFlash('success','修改成功');
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
     * Ajax无刷新删除 数据库修改字段
     */
    public function actionDelete(){
        header("Content-Type:text/json; charset=UTF-8");
        $id = $_POST['id'];
        $model = Brand::findOne($id);
        $model->status = '-1';
        if($model->save()){
            echo 1;
        }else{
            echo 0;
        };
    }
}

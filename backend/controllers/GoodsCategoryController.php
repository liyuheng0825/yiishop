<?php
namespace backend\controllers;

use backend\models\Category;
use backend\models\Goods;
use backend\models\GoodsCategory;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\web\Controller;
use yii\web\Request;

/**
 * 商品分类控制器
 * Class CreateGoodsController
 * @package backend\controllers
 */
class GoodsCategoryController extends Controller{
    /**
     * 显示商品分类
     */
    public function actionIndex(){
        $model = new GoodsCategory();
        $rows = $model->getAll();
        return $this->render('index',['rows'=>$rows]);
    }
    /**
     * 添加商品分类
     */
    public function actionAdd(){
        $model = new GoodsCategory();
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($model->parent_id){
                    //>>查询出父节点数据
                    $parend = GoodsCategory::findOne(['id' => $model->parent_id]);
                    //>>如果makeRoot的值不为0 证明创建子节点ID
                    $model->prependTo($parend);
                }else{
                    //>>如果makeRoot的值为0 证明创建根节点ID
                    $model->makeRoot();
                }
                $model->save();
                //>>提示信息
                \Yii::$app->session->setFlash('session','修改成功');
                //跳转
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    /**
     * 修改商品分类
     */
    public function actionEdit($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                    if ($model->parent_id){
                        //>>查询出父节点数据
                        $parend = GoodsCategory::findOne(['id' => $model->parent_id]);
                        //>>如果makeRoot的值不为0 证明创建子节点ID
                        $model->prependTo($parend);

                    }else{
                        //>>如果makeRoot的值为0 证明创建根节点ID
                        if ($model->getOldAttribute('parent_id')){
                            $model->makeRoot();
                        }else{
                            $model->save();
                        }
                    }
                    //>>提示信息
                    \Yii::$app->session->setFlash('session','修改成功');
                    //跳转
                    return $this->redirect(['index']);
                }
            }
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 删除节点
     * @param $id
     */
    public function actionDelete(){
        $id = $_POST['id'];
        if (GoodsCategory::findOne(['parent_id'=>$id])){
            return false;
        }else{
            GoodsCategory::deleteAll(['id'=>$id]);
            return 1;
        }
    }
}

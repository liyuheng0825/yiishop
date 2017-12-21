<?php
namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\web\Controller;

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
        $rows = GoodsCategory::find()->all();
        return $this->render('index',['rows'=>$rows]);
    }
    /**
     * 添加商品分类
     */
    public function actionAdd(){
        $model = new GoodsCategory();
        return $this->render('add',['model'=>$model]);
    }
}

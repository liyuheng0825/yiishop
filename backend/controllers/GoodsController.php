<?php
namespace backend\controllers;
use backend\models\Goods;
use yii\web\Controller;

/**
 * 商品表
 * Class GoodsController
 * @package backend\controllers
 */
class GoodsController extends Controller{
    /**
     * 商品列表
     */
    public function actionIndex(){
        $rows = Goods::find()->all();
        return $this->render('index',['rows'=>$rows]);
    }
    /**
     * 商品添加
     */
    public function actionAdd(){
        $model = new Goods();
     return $this->render('add',['model'=>$model]);
    }
}

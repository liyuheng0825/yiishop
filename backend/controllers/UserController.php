<?php
namespace backend\controllers;
use backend\models\User;
use yii\web\Controller;

/**
 * 管理员控制器
 * Class UserController
 * @package backend\controllers
 */
class UserController extends Controller{
    /**
     * 显示管理员列表
     */
    public function actionIndex(){
        $rows = User::find()->all();
        return $this->render('index',['rows'=>$rows]);
    }
}

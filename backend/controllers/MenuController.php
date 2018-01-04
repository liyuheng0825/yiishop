<?php
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

/**
 * 菜单控制器
 * Class MenuController
 * @package backend\controllers
 */
class MenuController extends Controller{
    /**
     * 显示菜单列表
     */
    public function actionIndex(){
        $menu = new Menu();
        $rows = $menu->getAll();
        return $this->render('index',['rows'=>$rows]);
    }
    /**
     * 添加菜单
     */
    public function actionAdd(){
       $model = new Menu();
       //设置顶级id
       $previous_menu = Menu::find()->where(['=','previous_menu','0'])->all();
       $menu =['0'=>'顶级菜单'];
       $menu += ArrayHelper::map($previous_menu,'id','name');

       //>>获取所有路由
        $permissions = \Yii::$app->authManager->getPermissions();
        $permission = ArrayHelper::map($permissions,'name','name');
        //>>提交组件
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                //>>提示
                \Yii::$app->session->setFlash('success','添加成功');
                //>>跳转
                return $this->redirect('index');
            }
        }

      return $this->render('add',['model'=>$model,'permission'=>$permission,'menu'=>$menu]);
    }
    /**
     * 修改
     * @param $id
     */
    public function actionEdit($id){
        $model = Menu::findOne($id);
        //设置顶级id
        $previous_menu = Menu::find()->where(['=','previous_menu','0'])->all();
        $menu =['0'=>'顶级菜单'];
        $menu += ArrayHelper::map($previous_menu,'id','name');

        //>>获取所有路由
        $permissions = \Yii::$app->authManager->getPermissions();
        $permission = ArrayHelper::map($permissions,'name','name');
        //>>提交组件
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                //>>提示
                \Yii::$app->session->setFlash('success','添加成功');
                //>>跳转
                return $this->redirect('index');
            }
        }

        return $this->render('add',['model'=>$model,'permission'=>$permission,'menu'=>$menu]);
    }
    /**
     * Ajax无刷新删除
     */
    public function actionDelete(){
        $id = \Yii::$app->request->post();
        $model = Menu::findOne(['id'=>$id]);
        $all = Menu::findOne(['previous_menu'=>$id]);
        if ($all){
            echo 0;
        }else{
            $model->delete();
            echo 1;
        }
    }
    public function behaviors(){
        return[
            'time'=>[
                'class'=>RbacFilter::className(),
            ],
        ];
    }
}

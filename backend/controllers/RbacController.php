<?php
namespace backend\controllers;
use backend\models\LoginForm;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\Controller;
use yii\web\Request;

/**
 * 权限控制器
 * Class RbacController
 * @package backend\controllers
 */
class RbacController extends Controller{
    /**
     * 权限列表
     */
    public function actionIndex(){
        $authManager = \Yii::$app->authManager;
        //>>获取所有
        $rows = $authManager->getPermissions();
        return $this->render('index-permission',['rows'=>$rows]);
    }
    /**
     * 权限添加
     */
    public function actionAdd(){
        $model = new PermissionForm();
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if($model->save()){
                    //>>提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    //>>跳转
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    /**
     * 修改
     * @param $name
     */
    public function actionEdit($name){
        $model = new PermissionForm();
        $authManager  = \Yii::$app->authManager->getPermission($name);
       //>>获取一条 赋值给表单模型
        $model->name=$authManager->name;
        $model->description=$authManager->description;
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if($model->edit($name)){
                    //>>提示
                    \Yii::$app->session->setFlash('success','修改成功');
                    //>>跳转
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    /**
     * 删除
     */
    public function actionDelete(){
    $name = $_POST['name'];
    //>>查询出一条
    $authManager = \Yii::$app->authManager->getPermission($name);
    //>>执行删除
    $result = \Yii::$app->authManager->remove($authManager);
    if ($result){
        return 1;
    }else{
        return 0;
    }
    }
    /**
     * 角色列表
     */
    public function actionIndexRole(){
        //>>获取所有角色
        $rows = \Yii::$app->authManager->getRoles();
        return $this->render('index-role',['rows'=>$rows]);
    }
    /**
     * 角色添加
     */
    public function actionAddRole(){
       $model =  new RoleForm();
       $permission = \Yii::$app->authManager->getPermissions();
       $permission = ArrayHelper::map($permission,'name','description');
       $request = new Request();
       if ($request->isPost){
           $model->load($request->post());
           if ($model->validate()){
               if ($model->save()){
                   //>>提示
                   \Yii::$app->session->setFlash('success','添加成功');
                   //>>跳转
                   return $this->redirect(['index-role']);
               }
           }
       }
       return $this->render('add-role',['model'=>$model,'permission'=>$permission]);
    }
    /**
     * 修改角色
     */
    public function actionEditRole(){

    }
}

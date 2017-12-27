<?php
namespace backend\controllers;
use backend\models\LoginForm;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;
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
        $model->scenario = PermissionForm::SCENARIO_ADD_PERMISSION;//>>场景权限
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
        $liyuheng='添加权限';
        return $this->render('add-permission',['model'=>$model,'liyuheng'=>$liyuheng]);
    }
    /**
     * 修改权限
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
        $liyuheng='修改权限';
        return $this->render('add-permission',['model'=>$model,'liyuheng'=>$liyuheng]);
    }
    /**
     * 删除权限
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
        $model->scenario = PermissionForm::SCENARIO_ADD_PERMISSION;//>>场景权限
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
       $liyuheng='添加角色';
       return $this->render('add-role',['model'=>$model,'permission'=>$permission,'liyuheng'=>$liyuheng]);
    }
    /**
     * 修改角色
     */
    public function actionEditRole($name){
        $model = new RoleForm();
        //>>根据name查询角色 名字和
        $val  = \Yii::$app->authManager->getRole($name);
        //>>赋值角色和描述
        $model->name=$val->name;
        $model->description=$val->description;
        //>>根据name查询权限
        $result = \Yii::$app->authManager->getPermissionsByRole($val->name);
        //>>对象中取 两个属性 转换为关联数组
        $p = ArrayHelper::map($result,'description','name');
        //>>转索引数组
        $p = array_values($p);
        $model->permission=$p;
        //>>查询出全部的权限
        $permission = \Yii::$app->authManager->getPermissions();
        $permission = ArrayHelper::map($permission,'name','description');
        //>>请求
        $request = new Request();
        if ($request->isPost){
        $model->load($request->post());
        if ($model->validate()){
            if ($model->update($name)){
                //>>提示
                \Yii::$app->session->setFlash('success','修改成功');
                //>>跳转
                return $this->redirect(['index-role']);
            }
        }
    }
        $liyuheng='修改角色';
        return $this->render('add-role',['model'=>$model,'permission'=>$permission,'liyuheng'=>$liyuheng]);
    }
    /**
     * 删除角色
     * @return int
     */
    public function actionDeleteRole(){
        $name = \Yii::$app->request->post('name');
        //>>查询出一条
        $authManager = \Yii::$app->authManager->getRole($name);
        //>>执行删除
        $result = \Yii::$app->authManager->remove($authManager);
        if ($result){
            return 1;
        }else{
            return 0;
        }
    }
}

<?php
namespace backend\models;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\rbac\Permission;

/**
 * 权限模型
 * Class Rbac
 * @package backend\models
 */
class PermissionForm extends Model{
    public $name;
    public $description;
    //>>场景
    const SCENARIO_ADD_PERMISSION = 'add';//>>定义添加常量场景
    //>>验证规则
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','examine','on'=>self::SCENARIO_ADD_PERMISSION]//>>该规则只在添加 生效
        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'路由/名称',
            'description'=>'描述',
        ];
    }
    /**
     * 添加判断是否存在
     */
    public function examine(){
        if (\Yii::$app->authManager->getPermission($this->name)){
            $this->addError('name','路由不能重复!');
        }
    }
    /**
     * 保存
     * @return bool
     */
    public function save()
    {
       $authManager = \Yii::$app->authManager;
       //>>保存
       $permission = new Permission();
       $permission->name = $this->name;
       $permission->description = $this->description;
       return $authManager->add($permission);
    }

    /**
     * 更新
     * @param $name
     * @return bool
     */
    public function edit($name){
        $authManager = \Yii::$app->authManager;
        //>>修改
        $permission = new Permission();
        $permission->name = $this->name;
        $permission->description = $this->description;
        //>>规则
        if ($name != $this->name){
            $p = \Yii::$app->authManager->getPermission($this->name);
            if ($p){
                $this->addError('name','路由已存在');
                return false;
            }
        }
        return $authManager->update($name,$permission);
    }
}
<?php
namespace backend\models;
use yii\base\Model;
use yii\rbac\Permission;
use yii\rbac\Role;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permission;
    //>>场景
    const SCENARIO_ADD_PERMISSION = 'add';//>>定义添加常量场景
    //>>验证规则
    public function rules()
    {
        return [
            [['name','description','permission'],'required'],
            ['name','examine','on'=>self::SCENARIO_ADD_PERMISSION]//>>该规则只在添加 生效
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色/身份',
            'description'=>'描述',
            'permission'=>'权限'
        ];
    }
    /**
     * 添加判断是否存在
     */
    public function examine(){
        if (\Yii::$app->authManager->getRole($this->name)){
            $this->addError('name','角色/身份不能重复!');
        }
    }
    /**
     * 保存角色
     * @return bool
     */
    public function save()
    {
        $authManager = \Yii::$app->authManager;
        $role = new Role();
        $role->name = $this->name;
        $role->description = $this->description;
        //>>保存角色
        $authManager->add($role);
        //>>保存权限
        foreach ($this->permission as $row) {
            $permission = $authManager->getPermission($row);
            $authManager->addChild($role,$permission);
        }
        return true;
    }
    /**
     * 更新角色
     */
    public function update($name){
        //>>规则 查询角色名是否存在
        if ($name != $this->name){
            $p = \Yii::$app->authManager->getRole($this->name);
            if ($p){
                $this->addError('name','角色/身份已存在!');
                return false;
            }
        }
        $authManager = \Yii::$app->authManager;
        $role = new Role();
        $role->name = $this->name;
        $role->description = $this->description;
        //>>更新角色
        $authManager->update($name,$role);
        //>>删除他的所有权限
        $authManager->removeChildren($role);
        //>>重新添加权限
        foreach ($this->permission as $row) {
            $permission = $authManager->getPermission($row);
            $authManager->addChild($role,$permission);
        }
        return true;
    }
}

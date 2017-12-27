<?php
namespace backend\models;
use yii\base\Model;
use yii\rbac\Permission;
use yii\rbac\Role;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permission;
    //>>验证规则
    public function rules()
    {
        return [
            [['name','description','permission'],'required']
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
     * 保存角色
     * @return bool
     */
    public function save(){
        $authManager = \Yii::$app->authManager;
        $role = new Role();
        $role->name = $this->name;
        $role->description = $this->description;
        $permission = $authManager->getPermission($this->permission);
        $authManager->add($role);
       return $authManager->addChild($role,$permission);
    }
}

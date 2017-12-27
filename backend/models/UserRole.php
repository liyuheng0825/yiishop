<?php
namespace backend\models;
use yii\base\Model;

class UserRole extends Model{
    public $role;//>>状态
    public function rules()
    {
        return [
            ['role', 'default', 'value' => null]
        ];
    }
    public function attributeLabels()
    {
        return [
          'role'=>'用户角色权限',
        ];
    }

    /**
     * 全部取消 在添加角色
     * @param $id
     */
    public function update($id){
        //>>删除全部角色
        $authManager = \Yii::$app->authManager;
        $authManager->revokeAll($id);
        //>>执行权限添加
        if ($this->role){
            //>>查询角色对象
            foreach ($this->role as $val){
                $part= $authManager->getRole($val);
                //>>保存角色到id
                $authManager->assign($part,$id);
            }
        }
        return true;
    }
}

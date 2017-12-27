<?php
namespace backend\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/*
 * 管理员模型
 */
class User extends ActiveRecord implements IdentityInterface {
    /*public $password;
    public $password_a;
    public $password_b;*/
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'password_reset_token'=>'确认密码',
            'email'=>'邮箱',
            'status'=>'状态',
           /*'password'=>'原密码',
            'password_a'=>'新密码',
            'password_b'=>'确认密码',*/
        ];
    }
    /**
     * 验证规则
     * @return array
     */
    public function rules()
    {
        return [
            [['username','password_hash','email','status'],'required'],//>>不能为空
            //[['password','password_a','password_b'],'exist'],//>>存在
            ['username', 'unique'],//>>唯一
            ['email', 'unique'],//>>唯一
            //['password_reset_token','detectionPwd'],//>>两次密码一致
            [['email'],'match','pattern'=>'/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/','message'=>'邮箱格式错误'],//>>邮箱格式
           // [['password_hash'],'match','pattern'=>'/^[a-zA-Z0-9]{6,20}+$/','message'=>'密码6-20位'],//>>密码格式
            //[['username'],'match','pattern'=>'/^[\u4e00-\u9fff\w]{5,16}$/','message'=>'5-16位由字母、数字、_或汉字组成'],//>>账号
            /*['password','Ppassword'],//>>修改查询原密码是否正确
            ['password_b','detectionPwd'],//判断修改的两次密码是否一致*/
        ];
    }
    /**
     * 验证密码是否一致
     */
/*    public function detectionPwd(){
        //var_dump($this->password_a!=$this->password_b);die;
        if ($this->password_a!=$this->password_b){
            $this->addError('password_b','新密码不一致');
        }
    }*/
//    /**
//     * 修改查询原密码是否一致
//     */
/*    public function Ppassword(){
        //var_dump($this->password,$this->password_hash);die;
        if(!\Yii::$app->security->validatePassword($this->password,$this->password_hash)){
           $this->addError('password','原密码错误');
        }
    }*/
//    /**
//     * 修改判断两次密码是否一致
//     */
//    public function Password(){
//        if ($this->password_a!=$this->password_b){
//            $this->addError('password_b','两次密码不一致');
//        }
//    }
    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;//>>自动登录
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
       return $this->getAuthKey() === $authKey;//>>自动登录接口
    }
}

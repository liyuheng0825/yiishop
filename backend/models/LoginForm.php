<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model {
    public $username;
    public $password;
    public $code;
    //标签名
    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'code'=>'验证码',
        ];
    }
    //验证规则
    public function rules()
    {
        return [
            //验证码验证规则
            ['code','captcha','captchaAction'=>'user/captcha'],
            //字段规则
            [['username','password'],'required'],
        ];
    }
   //登陆方法
    public function login(){
        //验证账号密码
        $user = User::find()->where(['username'=>$this->username])->andWhere(['status'=>1])->one();
        if($user){
            //用户名存在
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //密码正确
                //最后登录时间
                $user->last_login_time = time();
                //最后登录ip
                $user->last_login_ip = $_SERVER["REMOTE_ADDR"];
                //var_dump($user->finally_time,$user->finally_ip);die;
                $user->save(false);
                //保存到user
                \Yii::$app->user->login($user);
                return true;
            }else{
                //密码不正确
                $this->addError('password','密码不正确');
            }
        }else{
            //用户名不存在
            $this->addError('username','用户名不存在');
        }
        return false;
    }

}


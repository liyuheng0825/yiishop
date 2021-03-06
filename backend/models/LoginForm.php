<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model {
    public $username;
    public $password;
    public $code;
    public $auto;
    //标签名
    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'code'=>'验证码',
            'auto'=>'记住我'
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
            //自动登录
            ['auto','exist'],
        ];
    }
   //登陆方法
    public function login(){
        //验证账号密码
        $user = User::find()->where(['username'=>$this->username])->andWhere(['status'=>1])->one();
        if($user){
            //用户名存在
            //var_dump($this->password,$user->password_hash);die;
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //密码正确

                //最后登录时间
                $user->last_login_time = time();
                //最后登录ip
                $user->last_login_ip = $_SERVER["REMOTE_ADDR"];
                //var_dump($user->finally_time,$user->finally_ip);die;
                $user->save(false);
                //判断是否自动登录
                    if($this->auto){
                        \Yii::$app->user->login($user,7*24*3600);//>>七天内自动登录
                    }else{
                        //保存到user
                        \Yii::$app->user->login($user);
                    }
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


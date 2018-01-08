<?php
namespace frontend\models;
use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $chb;

    //验证规则
    public function rules()
    {
        return [
            //字段规则
            [['username','password'],'required'],
            //自动登录存在
            ['chb','default', 'value' => null],
        ];
    }
    public function login(){
        //验证账号密码
        $member = Member::find()->where(['username'=>$this->username])->one();
        if($member){
            //用户名存在
            if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                //最后登录时间
                $member->last_login_time = date("Ymd",time());
                //最后登录ip
                $member->last_login_ip = $_SERVER["REMOTE_ADDR"];
                $member->save(false);
                if ($this->chb){
                    //>>保存自动登录
                    \Yii::$app->user->login($member,7*24*3600);
                }else{
                    //>>保存登录状态
                    \Yii::$app->user->login($member);
                }
                return true;
            }else{
                //密码不正确
                echo "<script>alert('密码错误');</script>";
                header("refresh:0;url=http://lyh1.phpup.top/member/register");die;
            }
        }else{
            //用户名不存在
            echo "<script>alert('用户名不存在');</script>";
            header("refresh:0;url=http://lyh1.phpup.top/member/register");die;
        }
        return false;
    }
}

<?php
namespace backend\models;
use yii\base\Model;

class EditPassword extends Model{
    public $password;
    public $password_a;
    public $password_b;
    public function attributeLabels()
    {
        return [
            'password'=>'原密码',
             'password_a'=>'新密码',
             'password_b'=>'确认密码',
        ];
    }
    public function rules()
    {
        return [
            [['password','password_a','password_b'],'required'],//>>不能为空
            // [['password_hash'],'match','pattern'=>'/^[a-zA-Z0-9]{6,20}+$/','message'=>'密码6-20位'],//>>密码格式
            ['password','Ppassword'],//>>修改查询原密码是否正确
            ['password_b','detectionPwd'],//判断修改的两次密码是否一致
        ];
    }
    /**
     * 验证密码是否一致
     */
        public function detectionPwd(){
            if ($this->password_a!=$this->password_b){
                $this->addError('password_b','新密码不一致');
            }
        }
//    /**
//     * 修改查询原密码是否一致
//     */
        public function Ppassword(){
            //>>根据user查询原密码
            //>>获取当前user ID
            $id = \Yii::$app->user->identity->id;
            //>>获取登录后user所有信息
            $user = User::findOne(['id'=>$id]);
            if(!\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
               $this->addError('password','原密码错误');
            }
        }
}

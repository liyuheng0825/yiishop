<?php
namespace frontend\controllers;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\SignatureHelper;
use frontend\models\Useraddress;
use yii\web\Controller;
use yii\web\Request;

class MemberController extends Controller{
    public $enableCsrfValidation = false;
    //>>用户注册
    public function actionLogin()
    {
        $model = new Member();
        $request = new Request();
        if ($request->isPost){
           $model->load($request->post(),'');
           if ($model->validate()){
               $model->password_hash =\Yii::$app->security->generatePasswordHash($model->password);
               $model->created_at = time();
               $model->save(false);
               echo "<script>alert('注册成功')</script>";
               return $this->redirect('http://lyh1.phpup.top');
           }
        }
        return $this->render('login');
    }
    //>>用户登录
    public function actionRegister(){
        $model = new LoginForm();
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post(),'');
            if ($model->login()){
                //>>登录成功
                $cookies = \Yii::$app->request->cookies;//>>读cookie
                if ($cookies->has('cart')){//>>如果购物车存在
                    $value = $cookies->getValue('cart');
                    $cart = unserialize($value);//>>反序列化
                    foreach ($cart as $id=>$amount){
                        $model = Cart::find()->where(['goods_id'=>$id])->andWhere(['=','member_id',\Yii::$app->user->identity->id])->one();
                        if ($model){
                            //合并
                            $model->amount=$model->amount+$amount;
                            $model->save(false);
                        }else{
                            //添加
                            $model = new Cart();
                            $model->goods_id=$id;
                            $model->amount=$amount;
                            $model->member_id=\Yii::$app->user->identity->id;
                            $model->save(false);
                        }
                    }
                    //>>清除cookie
                    \yii::$app->response->cookies->remove('cart');
                }
                //echo "<script>alert('登录成功');</script>";
                return $this->redirect('http://lyh1.phpup.top');
            }
        }
        return $this->render('register');
    }
    //>>AJAX验证用户名唯一
    public function actionValidateUser($username){
        $row = Member::findOne(['username'=>$username]);
        if ($row){
            echo 'false';
        }else{
            echo 'true';
        }
    }
    //>>AJAX验证邮箱唯一
    public function actionValidateEmail($email){
        $row = Member::findOne(['email'=>$email]);
        if ($row){
            echo 'false';
        }else{
            echo 'true';
        }
    }
    //>>AJAX验证电话唯一
    public function actionValidateTel($tel){
        $row = Member::findOne(['tel'=>$tel]);
        if ($row){
            echo 'false';
        }else{
            echo 'true';
        }
    }
    //>>AJAX验证短信验证码
    public function actionValidateCode($captcha,$tel){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        if($redis->get('code_'.$tel)==null){
            return 'false';
        }else{
            //>>验证码成功
            if ($redis->get('code_'.$tel)==$captcha){
                return 'true';
            }else{
                return 'false';
            }
        }
    }
    //>>注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect('http://lyh1.phpup.top');
    }
    //>>用户地址显示 及添加表单
    public function actionAddress(){
            $rows = Useraddress::find()->where(['user'=>\Yii::$app->user->identity->username])->all();
            $model = new Useraddress();
                $post = \Yii::$app->request->post();
                $request = new Request();
                if ($request->isPost){
                    $model->load($request->post());
                    if ($model->validate()){
                        $model->user = \Yii::$app->user->identity->username;
                        $model->recipients = $post['recipients'];
                        $model->area = "{$post['cmbProvince']} {$post['cmbCity']} {$post['cmbArea']}";
                        $model->particular = $post['particular'];
                        $model->tel = $post['tel'];
                        if (!empty($post['state'])){
                            $model->state = 1;
                            //>>取消之前的默认状态
                            $state = Useraddress::find()->where(['=','user',\Yii::$app->user->identity->username])->andWhere(['=','state','1'])->one();
                            if ($state){
                                $state->state=0;
                                $state->save(false);
                            }
                        }else{
                            $model->state = 0;
                        }
                        $model->save(false);
                        return $this->redirect('address');
                    }
                }
            return $this->render('address',['rows'=>$rows,'model'=>$model]);
    }
    //>>删除地址
    public function actionDeleteAddress($id){
        $model = Useraddress::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect('address');
    }
    //>>修改默认地址
    public function actionMorenAddress($id){
        //>>把之前默认数据变成不默认
        $state = Useraddress::find()->where(['=','user',\Yii::$app->user->identity->username])->andWhere(['=','state','1'])->one();
        if ($state){
            $state->state=0;
            $state->save(false);
        }
        //>>默认当前数据
        $id = Useraddress::findOne(['id'=>$id]);
        $id->state=1;
        $id->save(false);
        return $this->redirect('address');
    }
    //>>修改地址
    public function actionEditAddress($id){
        $rows = Useraddress::find()->where(['user'=>\Yii::$app->user->identity->username])->all();
        $post = \Yii::$app->request->post();
        $request = new Request();
        $model = Useraddress::findOne(['id'=>$id]);
        $area = explode(' ',$model->area);
        $model->cmbProvince=$area[0];
        $model->cmbCity=$area[1];
        $model->cmbArea=$area[2];
        if ($request->isPost){
            $model->load($request->post(),'');
            if ($model->validate()){
                $model->user = \Yii::$app->user->identity->username;
                $model->recipients = $post['recipients'];
                $model->area = "{$post['cmbProvince']} {$post['cmbCity']} {$post['cmbArea']}";
                $model->particular = $post['particular'];
                $model->tel = $post['tel'];
                if (!empty($post['state'])){
                    $model->state = 1;
                    //>>取消之前的默认状态
                    $state = Useraddress::find()->where(['=','user',\Yii::$app->user->identity->username])->andWhere(['=','state','1'])->one();
                    if ($state){
                        $state->state=0;
                        $state->save(false);
                    }
                }else{
                    $model->state = 0;
                }
                $model->save(false);
                return $this->redirect('address');
            }
        }
        return $this->render('address',['model'=>$model,'rows'=>$rows]);
    }
    //>>短信验证
    public function actionSms($phone){
        //>>检查上一次手机号码发送的时间,间隔不能少于60秒
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        //>>ttl 获取key的剩余有效秒数
        $ttl =$redis->ttl('code_'.$phone);
        if ($ttl && $ttl>1800-60){
            //>>上一次发送短信少于60秒
            echo "<script>alert('距离上次发送时间不到60秒,请稍后再试!');</script>";
            exit;
        }
        $code = rand(1000,9999);
        $result = \Yii::$app->sms->send($phone,['code'=>$code]);
        if ($result->Code == 'OK'){
            //>>保存redis

            $redis->set('code_'.$phone,$code,30*60);
            return 'true';
        }else{
            var_dump($result);//>>错误 打印错误信息
        }
    }

}

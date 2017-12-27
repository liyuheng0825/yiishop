<?php
namespace backend\controllers;
use backend\models\EditPassword;
use backend\models\Login;
use backend\models\LoginForm;
use backend\models\User;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Request;

/**
 * 管理员控制器
 * Class UserController
 * @package backend\controllers
 */
class UserController extends Controller{
    /**
     * 显示管理员列表
     */
    public function actionIndex(){
        $rows = User::find()->where(['>','status','0'])->all();
        return $this->render('index',['rows'=>$rows]);
    }
    /**
     * 添加用户
     */
    public function actionAdd(){
       $model =  new User();
       $request = new Request();
       if ($request->isPost){
           $model->load($request->post());
           if ($model->validate()){
               //>>添加时间
               $model->created_at = time();
               //>>更新时间
               $model->updated_at = time();
               //>>处理密码
               $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
               //>>处理cookie值 随机生成32为字符串
               $model->auth_key = md5(uniqid(microtime(true),true));
               //>>执行保存
               $model->save();
               //>>提示信息
               \Yii::$app->session->setFlash('session','添加成功');
               //跳转
               return $this->redirect(['index']);
           }/*else{
               var_dump($model->getErrors());exit;
           }*/
       }
       return $this->render('add',['model'=>$model]);
    }
    /**
     * 修改用户信息
     */
    /*public function actionEdit($id){
       $model =  User::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                //>>更新时间
                $model->updated_at = time();
                //>>处理密码
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save();
                //>>提示信息
                \Yii::$app->session->setFlash('session','添加成功');
                //跳转
                return $this->redirect(['index']);
            }/*else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }*/
    /**
     * 删除
     * @param $id
     */
    public function actionDelete(){
        $id= $_POST['id'];
        $model = User::findOne($id);
        $model->status = 0;
        if($model->save(false)){
            echo 1;
        }else{
            echo 0;
        }
    }
    /**
     * 登录
     */
    public function actionLogin(){
       $model =  new LoginForm();
       $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->login()){
//                var_dump(\Yii::$app->user->isGuest);die;
                //提示信息
                \Yii::$app->session->setFlash('success','登陆成功');
                //跳转
                return $this->redirect(['index']);
            }
        }
       return $this->render('login',['model'=>$model]);
    }
    /**
     * 验证码
     */
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                //验证码设置
                'height'=>50,
                'minLength'=>4,
                'maxLength'=>4,
                'padding'=>0
            ]
        ];
    }
    /*
     * 注销
     */
    public function actionLogout(){
        \Yii::$app->user->logout();
        //>>提示
        \Yii::$app->session->setFlash('success', '注销成功');
        return $this->redirect(['login']);
    }
    /**
     * 过滤
     */
    public function behaviors(){
        return[
            'acf'=>[
                'class'=>AccessControl::className(),
                //'only'=>['captcha'],//>>验证码 都能访问
                //规则 不需要登录
                'rules'=>[
                    [
                        'allow'=>true,
                        'actions'=>['index','captcha','login'],
                        'roles'=>['?','@'],
                    ],
                    //登录后访问
                    [
                        'allow'=>true,
                        'actions'=>['add','edit','delete','logout','edit-password'],
                        'roles'=>['@'],
                    ]
                ]
            ]
        ];
    }
    /**
     * 修改密码
     */
    public function actionEditPassword(){
        $model = new EditPassword();
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $id = \Yii::$app->user->identity->id;
                //>>获取登录后user所有信息
                $user = User::findOne(['id'=>$id]);
                $user->password_hash = \Yii::$app->security->generatePasswordHash($model->password_a);
                $user->save(false);
                //跳转
                return $this->redirect(['logout']);
            }
        }
        return $this->render('edit_password',['model'=>$model]);
    }

}

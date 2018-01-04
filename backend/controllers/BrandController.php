<?php
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
/**
 * 品牌控制器
 * Class BrandController
 * @package frontend\controllers
 */
class BrandController extends Controller{

    //>>防止跨站攻击
    public $enableCsrfValidation = false;

    /**
     * 显示品牌列表
     */
    public function actionIndex(){
        $db = \Yii::$app->db;
        $sql = 'SELECT * FROM `brand` where status>=0';
        $rows = $db->createCommand($sql)->queryAll();

        return $this->render('index',['rows'=>$rows]);
    }
    /**
     * 添加品牌
     */
    public function actionAdd(){
        //>>所有请求模型
        $request = new Request();
        //>>banrd模型
        $model = new Brand();
        if ($request->isPost){
            //>>验证

            $model->load($request->post());

            if ($model->validate()){
                //>>执行添加
                $model->save(false);
                //>>提示
                \Yii::$app->session->setFlash('success','添加成功');
                //>>跳转
                return $this->redirect(['index']);
            }else{
                //>>打印错误
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 修改品牌
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
       $request =  new Request();
       $model = Brand::findOne($id);

        if ($request->isPost){
            $model->load($request->post());
            //>>验证成功
            if ($model->validate()){
                //>>执行添加
                $model->save(false);
                //>>提示
                \Yii::$app->session->setFlash('success','修改成功');
                //>>跳转
                return $this->redirect(['index']);
            }else{
                //>>打印错误
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    /**
     * Ajax无刷新删除 数据库修改字段
     */
    public function actionDelete(){
        header("Content-Type:text/json; charset=UTF-8");
        $id = $_POST['id'];
        $model = Brand::findOne($id);
        $model->status = '-1';
        if($model->save()){
            echo 1;
        }else{
            echo 0;
        };
    }
    /**
     * Ajax上传图片
     * 返回路径地址
     */
    public function actionUpload(){
        //>>接受图片
        $img = UploadedFile::getInstanceByName('file');
        //>>拼接地址
        $fileName = '/upload/brand/'.uniqid().'.'.$img->extension;
        //>>如果文件保存成功
        if ($img->saveAs(\Yii::getAlias('@webroot'.$fileName))){

            //===============七牛云上传================
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey ="3PGolvtj_RXpv8tan9q0FROW76r4tUhuxqCPhOFy";
            $secretKey = "II5UA2Ve0YB1Zpa9obF2FE9v1RR4K4Ju9IVGP_jj";
            $bucket = "yiishop-lyh";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot').$fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token,$key,$filePath);
            //echo "\n====> putFile result: \n";
            if ($err !== null) {
                //var_dump($err);
                //>>上传失败 返回1
                return json_encode(['error'=>1]);
            } else {
                //var_dump($ret);
                //>>上传成功 返回图片路径 http://<domain>/<key>
                $url='http://p1axxosn9.bkt.clouddn.com/'.$key;
                return json_encode(['url'=>$url]);
            }
            //=========================================
        }
    }
    public function behaviors(){
        return[
            'time'=>[
                'class'=>RbacFilter::className(),
                'except'=>['upload'],
            ],
        ];
    }
}

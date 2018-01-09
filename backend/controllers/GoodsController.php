<?php
namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Hits;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

/**
 * 商品表
 * Class GoodsController
 * @package backend\controllers
 */
class GoodsController extends Controller{
    //>>防止跨站攻击
    public $enableCsrfValidation = false;
    /**
     * 商品列表
     */
    public function actionIndex(){
        $query = Goods::find();
        $query->where(['>','status','0']);

        //>>搜索
        if ($_POST){
            //var_dump($date);die;
            if ($_POST['name']){
            $query->andWhere(['like','name',$_POST['name']]);
            }
            if ($_POST['sn']){
            $query->andWhere(['like','sn',$_POST['sn']]);
            }
            if ($_POST['market_price']){
            $query->andWhere(['like','market_price',$_POST['market_price']]);
            }
            if ($_POST['shop_price']){
            $query->andWhere(['like','shop_price',$_POST['shop_price']]);
            }
            //$rows =$query->all();
        }
            //$rows = $query->where(['>','status','0'])->all();
        //>>分页
        $pager = new Pagination(
            [
                'defaultPageSize'=>3,
                'totalCount'=>$query->count(),
            ]
        );
            //>>分页工具条
            $rows = $query->limit($pager->limit)->offset($pager->offset)->all();//limit 0[offset],2[limit]      第二页limit 2,2

        return $this->render('index',['rows'=>$rows,'pager'=>$pager]);
    }
    /**
     * 商品添加
     */
    public function actionAdd(){
        $model = new Goods();
        //>>goods_day_count 商品每日添加数 日志
        //>>时间处理
        $time = date('Ymd',time());
        $sn = $time*1000000+1;
        if(GoodsDayCount::findOne(['day'=> date('Y-m-d',time())])==null){
            //>>如果今天不存在
            $model->sn=$sn;
        }else{
            //>>如果今天存在
           $jt =  GoodsDayCount::findOne(['day'=> date('Y-m-d',time())]);
            $model->sn=($jt->count)+$sn;
        }
        //>>查询商品分类id和名字
        $goods = new GoodsCategory();
        $rows = $goods->getAll();
        $goods_category_id = ArrayHelper::map($rows,'id','name');
        //>>查询品牌分类id和名字
        $article = Brand::find()->select(['id','name'])->where(['>=','status','0'])->all();
        $article=ArrayHelper::map($article,'id','name');
        //>>商品详情
        $goods_intro = new GoodsIntro();
        //>>表单提交模型
        $request = new Request();

        if ($request->isPost){
            $model->load($request->post());
            $goods_intro->load($request->post());
            if ($model->validate()){
                    $model->add_time=time();
                    $model->save();
                    $goods_intro->goods_id=$model->id;
                    $goods_intro->save(false);
                    if (GoodsDayCount::findOne(['day'=>date('Y-m-d',time())])==null){
                        //如果今天不存在
                        $gdc  = new GoodsDayCount();
                        //>>时间
                        $gdc->day = $time;
                        //>>条数
                        $gdc->count = 1;
                        //>>执行添加
                        $gdc->save();
                    }else{
                        //>>如果今天存在
                        $gdc = GoodsDayCount::findOne(['day'=>date('Y-m-d',time())]);
                        $gdc->count = $gdc->count + 1;;
                        $gdc->save();
                    }
                    //>>游览量 默认1
                    $hits = new Hits();
                    $hits->goods_id = $model->id;
                    $hits->hits = 1;
                    $hits->save(false);
                    //>>生成静态文件模板
                    self::actionGoods($model->id);
                    //>>提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    //>>跳转
                    return $this->redirect(['goods/index']);

            }
        }

     return $this->render('add',['model'=>$model,'goods_category_id'=>$goods_category_id,'article'=>$article,'goods_intro'=>$goods_intro]);
    }
    //>>生成静态文件
    public function actionGoods($id){
        $row = Goods::findOne(['id'=>$id]);
        $hits = Hits::findOne(['goods_id'=>$id]);
        //>>点击数保存到redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('times_'.$id,$hits->hits);
        //var_dump($row->name);die;
        //>>商品相册
        $photo = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //>>商品详情
        $intro = GoodsIntro::findOne(['goods_id'=>$id]);
        //var_dump($photo);die;
        $result = $this->renderPartial('goods',['photo'=>$photo,'intro'=>$intro,'row'=>$row,'hits'=>$hits]);
        file_put_contents('../../frontend/web/'.$id.'.html',$result);
    }
    /**
     * UEditor 富文本编辑框
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config'=>[
                    'imageUrlPrefix'=>"http://lyh.phpup.top",//>>前台访问后台页面
                ]
            ]
        ];
    }
    /**
     * Ajax上传图片
     * 返回路径地址
     */
    public function actionUploader(){
        //>>接受图片
        $img = UploadedFile::getInstanceByName('file');

        //>>拼接地址
        $fileName = '/upload/goods/'.uniqid().'.'.$img->extension;

        //>>如果文件保存成功
        if ($img->saveAs(\Yii::getAlias('@webroot').$fileName)){

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
    /**
     * Ajax无刷新删除 数据库修改字段
     */
    public function actionDelete(){
        header("Content-Type:text/json; charset=UTF-8");
        $id = $_POST['id'];
        $model = Goods::findOne($id);
        $model->status = 0;
        if($model->save(false)){
            echo 1;
        }else{
            echo 0;
        };
    }
    /**
     * 修改
     */
    public function actionEdit($id)
    {
        $model = Goods::findOne($id);
        //>>查询商品分类id和名字
        $goods = new GoodsCategory();
        $rows = $goods->getAll();
        $goods_category_id = ArrayHelper::map($rows,'id','name');
        //>>查询品牌分类id和名字
        $article = Brand::find()->select(['id','name'])->where(['>=','status','0'])->all();
        $article=ArrayHelper::map($article,'id','name');
        //>>商品详情
        $goods_intro = GoodsIntro::findOne(['goods_id'=>$id]);
        //>>表单提交模型
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            $goods_intro->load($request->post());
            if ($model->validate()){
                    $model->status=1;
                    $model->save();
                    $goods_intro->save(false);
                    //>>生成静态文件模板
                    self::actionGoods($model->id);
                    //>>提示
                    \Yii::$app->session->setFlash('success','修改成功');
                    //>>跳转
                    return $this->redirect(['goods/index']);

            }
        }

        return $this->render('add',['model'=>$model,'goods_category_id'=>$goods_category_id,'article'=>$article,'goods_intro'=>$goods_intro]);
    }
    /**
     * 商品相册
     */
    public function actionPhoto(){
        $id = $_GET['id'];
        $rows = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        return $this->render('photo',['rows'=>$rows,'id'=>$id]);
    }

    /**
     * 添加商品相册
     * @return string
     */
    public function actionAddPhoto(){
        //>>接受图片
        $img = UploadedFile::getInstanceByName('file');
        $goods_id = $_GET['id'];
        //>>拼接地址
        $fileName = '/upload/goods/'.uniqid().'.'.$img->extension;

        //>>如果文件保存成功
        if ($img->saveAs(\Yii::getAlias('@webroot').$fileName)){

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
                $model = new GoodsGallery();
                $model->path = $url;
                $model->goods_id=$goods_id;
                $model->save(false);
                $id=$model->id;
                return json_encode(['url'=>$url,'goods_id'=>$goods_id,'id'=>$id]);
            }
            //=========================================
        }
    }
    /**
     * 相册删除
     */
    public function actionPhotoDelete(){
        $id = $_POST['id'];
        $model = GoodsGallery::findOne($id);
        if($model->delete()){
            echo 1;
        }else{
            echo 0;
        };
    }
    public function behaviors(){
        return[
            'time'=>[
                'class'=>RbacFilter::className(),
                'except'=>['uploader','upload','add-photo'],
            ],
        ];
    }
}

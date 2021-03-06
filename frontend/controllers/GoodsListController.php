<?php
namespace frontend\controllers;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use Codeception\Module\Redis;
use common\models\SphinxClient;
use frontend\models\Goods;
use frontend\models\GoodsIntro;
use frontend\models\Hits;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class GoodsListController extends Controller{
    /**
     * 点击列表显示商品
     * @param $id
     */
    public function actionList($id){
        $cate = GoodsCategory::findOne(['id'=>$id]);
        //>>根据深度来判断在哪一层
        if ($cate->depth==2){//>>如果是第三层
            $ids = [$id];
        }else{//>>如果是第一层及第二层
        $categories = $cate->children()->select('id')->andWhere(['depth'=>2])->asArray()->all();
        //>>转换数组
        $ids = ArrayHelper::map($categories,'id','id');
        }
        //>>查询商品
        $goods = Goods::find()->where(['in','goods_category_id',$ids])->all();
        //>>拼接html
        $html='';
        foreach ($goods as $row){
            $html.='<li>';
            $html.='<dl>';
            $html.='<dt><a href="/'.$row->id.'.html"><img src="'.$row->logo.'" alt="" /></a></dt>';
            $html.=' <dd><a href="/'.$row->id.'.html">'.$row->name.'</a></dt>';
            $html.='<dd><strong>￥'.$row->shop_price.'/斤</strong></dt>';
            $html.='<dd><a href="/'.$row->id.'.html"><em>已有10人评价</em></a></dt>';
            $html.='</dl>';
            $html.='</li>';
        }
        //>>分页
        $pager = new Pagination(
            [
                'defaultPageSize'=>3,
                'totalCount'=> Goods::find()->where(['in','goods_category_id',$ids])->count(),
            ]
        );



        return $this->render('list',['html'=>$html,'pager'=>$pager]);
    }
    /**
     * 首页分类导航 保存redis
     * @return bool|string
     */
    public static function getcategory(){
        //>>使用redis缓存
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        //$redis->del('category_html');
        $html = $redis->get('category_html');
        if ($html==false){//>>如果redis不存在
            $category = GoodsCategory::find()->where(['parent_id'=>0])->all();
            foreach ($category as $val=>$key) {//>>第一层
                $html .= '<div class="cat'.($val?'':' item1').'">';
                $html .= '<h3><a href="/goods-list/list?id='.$key->id.'">'.$key->name.'</a><b></b></h3>';
                $html .= '<div class="cat_detail">';
                $category1 = GoodsCategory::find()->where(['parent_id' => $key->id])->all();
                foreach ($category1 as $val1=>$key1) {//>>第二层
                    $html .= '<dl '.($val1?'':'class="dl_1st"').'>';
                    $html .= '<dt><a href="/goods-list/list?id='.$key1->id.'">'.$key1->name.'</a></dt>';
                    $html .= '<dd>';
                    $category2 = GoodsCategory::find()->where(['parent_id' => $key1->id])->all();
                    foreach ($category2 as $key2) {//>>第三层
                        $html .= '<a href="/goods-list/list?id='.$key2->id.'">'.$key2->name.'</a>';
                    }
                    $html .= '</dd >';
                    $html .= '</dl >';
                }
                $html .= ' </div>';
                $html .= '</div>';
            }
            //>>保存redis
            $redis->set('category_html',$html,24*3600);
        }
        return $html;
    }
    /**
     *  商品详情
     * @param $id
     * @return string
     */
/*    public function actionGoods($id){
        //>>记录点击数
        $model = Hits::findOne(['goods_id'=>$id]);
        if ($model){//如果该商品游览记录存在
            //Hits::updateAllCounters(['hits'=>1]);//>>更新计时器
            $model->hits=$model->hits+1;
            $model->save(false);
        }else{//如果该商品的游览记录不存在
            $model = new Hits();
            $model->goods_id=$id;
            $model->hits=1;
            $model->save(false);
        }
        //>>缓存
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $hits = $redis->get($id.'hits');//>>取
        if ($hits==false){//>>如果缓存不存在 那么就重新保存缓存 并且设置过期时间
            $m = Hits::findOne(['goods_id'=>$id]);
            $redis->set($id.'hits',$m->hits,10);//>>每10秒更新缓存
        }
        $row = Goods::findOne(['id'=>$id]);
        //var_dump($row->name);die;
        //>>商品相册
        $photo = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //>>商品详情
        $intro = GoodsIntro::findOne(['goods_id'=>$id]);
        //var_dump($photo);die;
        return $this->render('goods',['photo'=>$photo,'intro'=>$intro,'row'=>$row]);
    }*/
    //>>搜索
    public function actionSearch($name){
        //调用分词搜索出的数据
        $ids = self::actionCoreseek($name);
        //>>查询商品
        $goods = Goods::find()->where(['in','id',$ids])->all();
        //>>如果商品没有
        if (!$goods){
            return $this->renderPartial('error');
        }
        //>>拼接html
        $html='';
        foreach ($goods as $row){
            $html.='<li>';
            $html.='<dl>';
            $html.='<dt><a href="/goods-list/goods?id='.$row->id.'"><img src="'.$row->logo.'" alt="" /></a></dt>';
            $html.=' <dd><a href="/goods-list/goods?id='.$row->id.'">'.$row->name.'</a></dt>';
            $html.='<dd><strong>￥'.$row->shop_price.'/斤</strong></dt>';
            $html.='<dd><a href="/goods-list/goods?id='.$row->id.'"><em>已有10人评价</em></a></dt>';
            $html.='</dl>';
            $html.='</li>';
        }
        //>>分页
        $pager = new Pagination(
            [
                'defaultPageSize'=>3,
                'totalCount'=> Goods::find()->where(['like','name',$name])->count(),
            ]
        );

        return $this->render('list',['html'=>$html,'pager'=>$pager]);
    }
    //>>分词搜索
    public function actionCoreseek($name){

        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
        $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
        $cl->SetLimits(0, 1000);
        $res = $cl->Query($name, 'goods');//shopstore_search
        $ids=[];
        if (isset($res['matches'])){
            foreach($res['matches'] as $match){
                $ids[] =$match['id'];
            }
        }
        return $ids;
    }
    //>>静态页面判断用户是否登录
    public function actionUser(){
    if (!\Yii::$app->user->isGuest){
        $result['a']=true;
        $result['b']=\Yii::$app->user->identity->username;
        }else{
            $result['a']=false;
            $result['b']='';
        }
    return json_encode($result);
    }
    //>>静态页面商品游览人数
    public function actionHist($id){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $times = $redis->incr('times_'.$id);
        return json_encode($times);
    }
    //>>每天凌晨3点执行
    public function actionHistRedis(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $hits = Hits::find()->all();//>>获取所有商品的游览量
        foreach ($hits as $h){
            if ($redis->get('times_'.$h->goods_id)){
                $h->hits=$redis->get('times_'.$h->goods_id);
                $h->save(false);
            }
        }
        echo '保存访问量成功!'.date('Y-m-d H:i:s',time());
    }
    /**
     *设置超时未支付订单
     * */
    public function actionClean(){
        //>>零时修改php生命周期配置
            $orders = Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-24*3600])->all();
            foreach ($orders as $order){
                $order->status = 0;
                $order->save();
                //>>返还库存
                $order_goods = OrderGoods::find()->where(['order_id'=>$order->id])->all();
                foreach ($order_goods as $goods){
                    //>>计数器 增加多少 第二个参数条件
                    Goods::updateAllCounters(['stock'=>$goods->amount],['id'=>$goods->goods_id]);
                }
            }
            echo '待支付超时订单清理完成'.date('Y-m-d H:i:s',time());
    }
/*    //>>job
    public function actionRedis(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('name','张三',30);//a将name= “张三”保存到redis，并设置30秒后过期
        $redis->set('age',18);//b将age=  18 保存到redis
    }
    public function actionTtl(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        if ($redis->get('name')){//如果不存在
            $redis->incr('age');//age自加1
        }else{
            $redis->decr('age');//age自减1
        }
        echo $redis->get('age');
    }*/

}

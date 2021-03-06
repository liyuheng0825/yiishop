<?php
namespace console\controllers;
use frontend\models\Goods;
use frontend\models\Hits;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\console\Controller;

class TaskController extends Controller {
    /**
     *设置超时未支付订单
     * */
    public function actionClean(){
        //>>零时修改php生命周期配置
        set_time_limit(0);
        while (true){//>>死循环 自动执行
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
            echo '待支付超时订单清理完成'.date('H:i:s')."\n";
            //>>每10秒执行一次
            sleep(10);
        }
    }
    //>>每天凌晨3点执行
    public function actionHist(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $hits = Hits::find()->all();//>>获取所有商品的游览量
        foreach ($hits as $h){
            if ($redis->get('times_'.$h->goods_id)){
                $h->hits=$redis->get('times_'.$h->goods_id);
                $h->save(false);
            }
        }
        echo '点击量备份完毕'.date('H:i:s')."\n";
    }
}

<?php
namespace frontend\controllers;
use frontend\models\Cart;
use frontend\models\Goods;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;


class CartController extends Controller{
    //>>防表单提交注入
    public $enableCsrfValidation = false;
    //>>购物车
    public function actionIndex(){
        //>>判断用户是否登录
        if (\Yii::$app->user->isGuest){//>>没登录 读cookie
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('cart');
            $carts = unserialize($value);
            if ($carts){//>>没登录 购物车有数据
                $goods_ids = array_keys($carts);
                $rows = Goods::find()->where(['in','id',$goods_ids])->all();
            }else{//>>没登录 购物车没数据
                $html='';
                return $this->render('cart',['html'=>$html]);
            }
        }else{//>>登录 取数据库
            //>>查询出该用户的购物车
            $carts = Cart::find()->where(['=','member_id',\Yii::$app->user->identity->id])->all();
            $carts = ArrayHelper::map($carts,'goods_id','amount');//>>只取Goods_id 和amount
            foreach ($carts as $key=>$cart){
                $rows[] = Goods::find()->where(['id'=>$key])->one();
            }
        }

        $html='';
        //>>遍历购物车一共有多少条数据
        foreach ($rows as $row){
            $html.= '<tr date-id="'.$row->id.'">';
            $html.='<td class="col1"><a href=""><img src="'.$row->logo.'" alt="" /></a><strong><a href="">'.$row->name.'</a></strong></td>';
            $html.='<td class="col3">￥<span>'.$row->shop_price.'/斤</span></td>';
            $html.='<td class="col4">';
            $html.='<a href="javascript:;" class="reduce_num"></a>';
            $html.='<input type="text" name="amount" value="'.$carts[$row->id].'" class="amount"/>';
            $html.='<a href="javascript:;" class="add_num"></a>';
            $html.='</td>';
            $html.='<td class="col5">￥<span>'.$row->shop_price*$carts[$row->id].'</span></td>';
            $html.='<td class="col6"><a href="'.Url::to(['cart/delete']).'?id='.$row->id.'">删除</a></td>';
            $html.='</tr>';
        }
        return $this->render('cart',['html'=>$html]);
    }
    //>>添加购物车信息
    public function actionAddCart(){
        $post = \Yii::$app->request->post();
        $goods_id = $post['goods_id'];
        $amount = $post['amount'];
        //>>判断是否登录
        if (\Yii::$app->user->isGuest){//>>没登录 保存cookie
            //>>判断有没有cookie
            $cookies = \Yii::$app->request->cookies;//>>读cookie
            if ($cookies->has('cart')){//>>如果cookie存在
                $value = $cookies->getValue('cart');
                $cart = unserialize($value);//>>反序列化
            }else{//>>如果cookie不存在 赋空数组
                $cart=[];
            }
            //>>写cookie
            //>>cookie中的格式 [1=>2,5=>10];
            //>>判断商品是否存在 存在就累加
            if (array_key_exists($goods_id,$cart)){//>>如果商品存在
                $cart[$goods_id] += $amount;
            }else{//>>如果商品不存在
                $cart[$goods_id] = $amount;
            }
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'cart';
            $cookie->value =serialize($cart);//>>序列化
            $cookies->add($cookie);
        }else{
            //>>登录 保存数据库
            $model = new Cart();
            //>>如果商品id相同那么就合并
            $carts = Cart::find()->where(['goods_id'=>$post['goods_id']])->one();
            if($carts){
                //>>数量相加
                $carts->amount=$carts->amount+$post['amount'];
                $carts->save(false);
            }else{//>>如果商品id不同
                $model->goods_id = $post['goods_id'];
                $model->amount = $post['amount'];
                $model->member_id = \Yii::$app->user->identity->id;
                $model->save(false);
            }
            //>>当用户登录的时候,将cookie中的数据自动同步到数据表中
            //>>如果已经有这个商品,就合并cookie中的数量
            //>>如果没有这个商品,就添加这个商品到购物车表
        }
        return $this->redirect('index');
    }
    //>>删除购物出数据
    public function actionDelete($id){
        if (\Yii::$app->user->isGuest){//没登录 删除该cookie id
            $json = \Yii::$app->request->cookies->getValue('cart');
            $cart = unserialize($json);
            foreach ($cart as $good_id=>$amount){
                if ($id==$good_id){
                    //>>删除
                    unset($cart[$good_id]);
                }
            }
            //>>再保存cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'cart';
            $cookie->value =serialize($cart);//>>序列化
            $cookies->add($cookie);
        }else{//>>登录情况下 删除数据库
            $model = Cart::find()->where(['goods_id'=>$id])->one();
            $model->delete();
        }
        //>>跳转
        return $this->redirect('index');
    }
    //>>AJAX修改数量
    public function actionChange(){
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if (\Yii::$app->user->isGuest){//>>如果没登录
            $cookies = \Yii::$app->request->cookies;//>>读cookie
            if ($cookies->has('cart')){//>>如果cookie存在
                $value = $cookies->getValue('cart');
                $cart = unserialize($value);//>>反序列化
            }else{//>>如果cookie不存在 赋空数组
                $cart=[];
            }
            foreach ($cart as $good_id=>$key){
                if ($goods_id==$good_id){
                    //>>修改商品数量
                    $cart[$good_id]=$amount;
                }
            }
            //>>再保存cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'cart';
            $cookie->value =serialize($cart);//>>序列化
            $cookies->add($cookie);
        }else{//>>如果登录 操作数据库
            $model = Cart::findOne(['goods_id'=>$goods_id]);
            $model->amount = $amount;
            $model->save(false);
        }
    }
    //>>结算 订单
    public function actionIndent(){
        if (\Yii::$app->user->isGuest){//如果结算 没登录
            return $this->redirect(['member/register']);
        }else{//如果登录 那么跳转结算页面
            return $this->render('indent');
        }
    }
}

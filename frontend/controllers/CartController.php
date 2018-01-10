<?php
namespace frontend\controllers;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderExpressage;
use frontend\models\OrderGoods;
use frontend\models\OrderPayment;
use frontend\models\Useraddress;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;


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
            if (!$carts){//>>登录 没数据
                $html='';
                return $this->render('cart',['html'=>$html]);
            }
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
        }else {//如果登录 那么跳转结算页面
            if (Cart::findOne(['member_id' => \Yii::$app->user->id])){//>>如果该用户购物车中有数据
                $user = \Yii::$app->user->identity->username;
            //>>获取该用户地址数据
            $address = Useraddress::find()->where(['user' => $user])->all();
            //>>获取该用户的购物车信息
            $id = \Yii::$app->user->identity->id;
            $carts = Cart::find()->where(['member_id' => $id])->all();
            $html = '';
            $quantity = '';//>>购物车的商品总数量
            $money = '';//>>购物车的商品总金额
            foreach ($carts as $cart) {
                $goods = Goods::findOne(['id' => $cart->goods_id]);
                $html .= '<tr>';
                $html .= '<td class="col1"><a href=""><img src="' . $goods->logo . '" alt="" /></a>  <strong><a href="">' . $goods->name . '</a></strong></td>';
                $html .= '<td class="col3">' . $goods->shop_price . '</td>';
                $html .= '<td class="col4">' . $cart->amount . '</td>';
                $html .= '<td class="col5"><span>￥' . $cart->amount * $goods->shop_price . '</span></td>';
                $html .= '</tr>';
                $quantity += $cart->amount;
                $money += $cart->amount * $goods->shop_price;
            }
            //>>获取送货方式
            $expressage = OrderExpressage::find()->where(['state' => 1])->all();
            //>>获取支付方式
            $payment = OrderPayment::find()->where(['state' => 1])->all();
            //>>提交订单表
            $post = \Yii::$app->request->post();
            $request = new Request();
            if ($request->isPost) {
                //>>获取收件人详情信息
                $address = Useraddress::findOne(['id' => $post['address_id']]);
                $model = new Order();
                //>>用户id
                $model->member_id = \Yii::$app->user->id;
                //>>收件人
                $model->name = $address->recipients;
                //>>处理省市县
                $area = explode(' ', $address->area);
                $model->province = $area[0];
                $model->city = $area[1];
                $model->area = $area[2];
                //>>详细地址
                $model->address = $address->particular;
                //>>收件人电话
                $model->tel = $address->tel;
                //>>根据配送方式id查询配送方式
                $ex = OrderExpressage::findOne(['id' => $post['delivery']]);
                //>>配送方式id
                $model->delivery_id = $ex->id;
                //>>配送方式名称
                $model->delivery_name = $ex->name;
                //>>配送价格
                $model->delivery_price = $ex->freight;
                //>>根据支付方式id查询支付方式
                $pa = OrderPayment::findOne(['id' => $post['pay']]);
                //>>支付方式id
                $model->payment_id = $pa->id;
                //>>支付方式名称
                $model->payment_name = $pa->name;
                //>>订单总金额
                $model->total = $money+$ex->freight;
                //>>状态 待付款
                $model->status = 1;
                //>>第三方支付
                //>>创建时间
                $model->create_time = time();
                //>>操作数据库 开启事务
                $transaction = \Yii::$app->db->beginTransaction();
                try{//>>尝试运行
                    if ($model->validate($request->post(),'')){
                        if ($model->save()) {//>>执行成功保存 订单商品详情表order_goods
                            //>>购物车里面所有的商品
                            foreach ($carts as $cart) {
                                $goods_model = new OrderGoods();
                                $goods_model->order_id = $model->id;
                                $goods = Goods::findOne(['id' =>$cart->goods_id]);
                                //>>如果订单中商品的数量大于该商品的总数量
                                if ($goods->stock>=$cart->amount){
                                    //>>订单详情表
                                    $goods_model->goods_id = $goods->id;
                                    $goods_model->goods_name = $goods->name;
                                    $goods_model->logo = $goods->logo;
                                    $goods_model->price = $goods->shop_price;
                                    $goods_model->amount = $cart->amount;
                                    $goods_model->total = $goods->shop_price * $cart->amount;
                                    if ($goods_model->validate($request->post(),'')){
                                        if ($goods_model->save()) {
                                            //>>该商品数量减少
                                            $goods->stock-=$cart->amount;
                                            $goods->save(false);
                                            //>>删除购物车信息
                                            $cart = Cart::find()->where(['member_id' => $cart->member_id])->all();//>>提交成功清空购物车
                                            foreach ($cart as $cat) {
                                                $cat->delete();
                                            }

                                        }
                                    }
                                }else{//>>抛出异常
                                    throw new Exception('该商品数量不足');
                                }
                            }
                            //>>遍历结束
                            //>>提交事务
                            $transaction->commit();
                            //>>发送邮件
                             $em=\Yii::$app->mailer->compose()
                                 ->setFrom('10943575@qq.com')//>>发件人
                                 ->setTo(\Yii::$app->user->identity->email)//>>收件人
                                 ->setSubject('商城订单成功信息')//>>邮件主题
                                 ->setHtmlBody('<h1 style="color: red;">亲爱的'.\Yii::$app->user->identity->username.'您的订单已下单成功,请尽快支付哟!</h1>')//>>邮件内容
                                 ->send();
                            //>>跳转到视图控制器(出口)
                            return $this->redirect('refer');
                        }
                    }
                }catch (Exception $e){//>>捕获异常
                    $transaction->rollBack();//>>回滚
                    //>>跳转
                    echo "<script>alert('该商品数量不足');</script>";
                    header("refresh:0;url=http://lyh1.phpup.top/cart/index");die;
                }
            }
            return $this->render('indent', ['address' => $address, 'html' => $html, 'expressage' => $expressage, 'payment' => $payment, 'quantity' => $quantity, 'money' => $money]);
            }else{
                //>>该用户数购物车中没有数据
                echo "<script>alert('购物车已经清空啦!请先购物.............');</script>";
                header("refresh:0;url=http://lyh1.phpup.top/");die;
            }
        }
    }
    //>>订单提交成功视图
    public function actionRefer(){
        return $this->render('refer');
    }
    //>>查看订单状态
    public function actionOrderState(){
        //>>判断是否登录
        if (\Yii::$app->user->isGuest){
            return $this->redirect(['member/register']);//>>登录页面
        }else{
        //>>根据用户查询订单表
        $order = Order::find()->where(['member_id'=>\Yii::$app->user->id])->andWhere(['status'=>1])->orderBy('id desc')->all();
        //>>根据订单id查询订单商品
        $html='';
        foreach ($order as $or){
            if ($or->status==0){
                $status =  '已取消';
            }
            if ($or->status==1){
                $status = '代付款';
            }
            if ($or->status==2){
                $status = '待发货';
            }
            if ($or->status==3){
                $status = '待收货';
            }
            if ($or->status==4){
                $status = '完成';
            }
            $goods_or = OrderGoods::find()->where(['order_id'=>$or->id])->all();
            foreach ($goods_or as $g){
                $html.='<tr>';
                $html.='<td><a href="">'.$or->id.'</a></td>';
                $html.='<td><a href=""><img src="'.$g['logo'].'" alt="" />'.$g['goods_name'].'</a></td>';
                $html.='<td>'.$or->name.'</td>';
                $html.='<td>￥'.$or->total.' '.$or->payment_name.'</td>';
                $html.='<td>'.date("Y-m-d H:i:s",$or->create_time).'</td>';
                $html.='<td>'.$status.'</td>';
                $html.='<td><a href="">查看</a> | <a href="">删除</a></td>';
                $html.='</tr>';
            }
        }
        return $this->render('state',['html'=>$html]);
        }
    }
}

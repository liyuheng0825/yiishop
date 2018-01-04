<?php
namespace frontend\controllers;
use frontend\models\Cart;
use frontend\models\Goods;
use yii\web\Controller;

class CartController extends Controller{
    //>>防表单提交注入
    public $enableCsrfValidation = false;
    //>>购物车
    public function actionIndex(){
        //>>查询出该用户的购物车
        $carts = Cart::find()->where(['=','member_id',\Yii::$app->user->identity->id])->all();
        $html='';
        //>>遍历购物车一共有多少条数据
        foreach ($carts as $cart){
                $row = Goods::find()->where(['id'=>$cart->goods_id])->one();
                    $html.= '<tr>';
                    $html.='<td class="col1"><a href=""><img src="'.$row->logo.'" alt="" /></a><strong><a href="">'.$row->name.'</a></strong></td>';
                    $html.='<td class="col3">￥<span>'.$row->shop_price.'/斤</span></td>';
                    $html.='<td class="col4">';
                    $html.='<a href="javascript:;" class="reduce_num"></a>';
                    $html.='<input type="text" name="amount" value="'.$cart->amount.'" class="amount"/>';
                    $html.='<a href="javascript:;" class="add_num"></a>';
                    $html.='</td>';
                    $html.='<td class="col5">￥<span>'.$row->shop_price*$cart->amount.'</span></td>';
                    $html.='<td class="col6"><a href="">删除</a></td>';
                    $html.='</tr>';
        }
        return $this->render('cart',['html'=>$html]);
    }
    //>>添加购物车信息
    public function actionAddCart(){
        $post = \Yii::$app->request->post();
        //var_dump($post);die;
        //>>判断是否登录
        if (\Yii::$app->user->isGuest){//>>没登录 保存cookie
            //>>判断有没有cookie
            if(isset($_COOKIE[$post['member_id']])){//>>cookie存在
                //>>读cookie
                $cookie = \Yii::$app->request->cookies;
                $username = $cookie->getValue($post['member_id']);
                var_dump($username);
                die;
            }else{//>>cookie不存在
                //>>写cookie
                $cookies = \Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => $post['member_id'],
                    'value' => $post,
                ]));
            }
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
                $model->member_id = $post['member_id'];
                $model->save(false);
            }
            //>>当用户登录的时候,将cookie中的数据自动同步到数据表中
            //>>如果已经有这个商品,就合并cookie中的数量
            //>>如果没有这个商品,就添加这个商品到购物车表
        }
        return $this->redirect('index');
    }
}

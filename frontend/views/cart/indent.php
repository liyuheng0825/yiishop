<?php $this->context->layout='head'; //不使用布局?>
<link rel="stylesheet" href="/style/base.css" type="text/css">
<link rel="stylesheet" href="/style/global.css" type="text/css">
<link rel="stylesheet" href="/style/header.css" type="text/css">
<link rel="stylesheet" href="/style/fillin.css" type="text/css">
<link rel="stylesheet" href="/style/footer.css" type="text/css">

<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/js/cart2.js"></script>
<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="<?=\yii\helpers\Url::to('http://lyh1.phpup.top')?>"><img src="/images/1.png" alt="商城" width="200"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <form action="" method="post">
            <div class="address_info">
                <p>
                    <?php foreach ($address as $row):?>
                <input type="radio" value="<?=$row->id?>" name="address_id" <?=$row->state==1?'checked="checked"':''?>/><?=$row->recipients.' '.$row->tel.' '.$row->area.' '.$row->particular?></p>
                <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($expressage as $ex):?>
                    <tr <?=$ex->id==1?'class="cur"':''?> id="mytr">
                        <td>
                            <input type="radio" id="myinput" name="delivery" value="<?=$ex->id?>" <?=$ex->id==1?'checked="checked"':''?> much="<?=$ex->freight?>"/><?=$ex->name?>
                        </td>
                        <td>￥<?=$ex->freight?></td>
                        <td><?=$ex->standard?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach ($payment as $p):?>
                    <tr <?=$p->id==1?'class="cur"':''?>>
                        <td class="col1"><input type="radio" name="pay" value="<?=$p->id?>" <?=$p->id==1?'checked="checked"':''?>/><?=$p->name?></td>
                        <td class="col2"><?=$p->explain?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->
        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?=$html?>
                <!--<tr>
                    <td class="col1"><a href=""><img src="/images/cart_goods1.jpg" alt="" /></a>  <strong><a href="">【1111购物狂欢节】惠JackJones杰克琼斯纯羊毛菱形格</a></strong></td>
                    <td class="col3">￥499.00</td>
                    <td class="col4"> 1</td>
                    <td class="col5"><span>￥499.00</span></td>
                </tr>-->
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$quantity?>件商品，总商品金额：</span>
                                <em id="myem"><?=$money?></em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="yunfei">￥10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em id="zong"><?=$money+10?></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <p>应付总额：<strong id="money">￥<?=$money+10?>元</strong></p>
        <button>提交订单</button>
    </div>
    </form>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<script type="text/javascript">
   $("table").on('click','td #myinput',function () {
       //>>获取input的值
       var v = $(this).attr('much');
       //>>获取原来的总金额
       var m=$('#myem').html();
       var k = parseFloat(v)+parseFloat(m);
       $('#money').html(k+'元');
       $('#zong').html(k);
       $('#yunfei').html(v);
   })

</script>
<!-- 底部版权 end -->
</body>
</html>

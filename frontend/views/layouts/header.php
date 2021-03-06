<?php
/* @var $content string */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <title>水果之家 非静态</title>


    <link rel="stylesheet" href="/style/home.css" type="text/css">
    <link rel="stylesheet" href="/style/address.css" type="text/css">
    <link rel="stylesheet" href="/style/goods.css" type="text/css">
    <link rel="stylesheet" href="/style/common.css" type="text/css">
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/index.css" type="text/css">
    <link rel="stylesheet" href="/style/bottomnav.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
    <!--判断路径 商品列表-->
    <?php if (Yii::$app->request->getPathInfo()=='goods-list/list'){
        echo '<link rel="stylesheet" href="/style/list.css" type="text/css">';
    }?>



    <script type="text/javascript" src="/js/jsAddresss.js"></script>
    <script type="text/javascript" src="/js/home.js"></script>
    <script type="text/javascript" src="/js/list.js"></script>
    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/header.js"></script>
    <script type="text/javascript" src="/js/goods.js"></script>
    <script type="text/javascript" src="/js/index.js"></script>
    <script type="text/javascript" src="/js/jqzoom-core.js"></script>

</head>
<body>
	<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w1210 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
					<li>您好，欢迎来到水果之家！
                        <?php
                        if (Yii::$app->user->isGuest){
                            echo'[<a href="'.\yii\helpers\Url::to(['member/register']).'">登录</a>]
                        [<a href="'.\yii\helpers\Url::to(['member/login']).'">免费注册</a>]';
                        }else{
                            echo '[<a href="#">'.Yii::$app->user->identity->username.'</a>]
                        [<a href="'.\yii\helpers\Url::to(['member/logout']).'">注销</a>]
                        </li><li class="line">|</li>
                        <li><a href="'.\yii\helpers\Url::to(['cart/order-state']).'">我的订单</a></li>';
                        }
                        ?>
					<li class="line">|</li>
					<li>客户服务</li>

				</ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>

	<!-- 头部 start -->
	<div class="header w1210 bc mt15">
		<!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
		<div class="logo w1210">
			<h1 class="fl"><a href="<?=\yii\helpers\Url::to('http://lyh1.phpup.top')?>"><img src="/images/1.png" alt="京西商城" width="200"></a></h1>
			<!-- 头部搜索 start -->
			<div class="search fl">
				<div class="search_form">
					<div class="form_left fl"></div>
					<form action="<?=\yii\helpers\Url::to(['goods-list/search'])?>" name="serarch" method="get" class="fl">
						<input type="text" name="name" class="txt" placeholder="请搜索商品关键字" /><input type="submit" class="btn" value="搜索"/>
					</form>
					<div class="form_right fl"></div>
				</div>
				
				<div style="clear:both;"></div>

				<div class="hot_search">
					<strong>热门搜索:</strong>
					<a href="">D-Link无线路由</a>
					<a href="">休闲男鞋</a>
					<a href="">TCL空调</a>
					<a href="">耐克篮球鞋</a>
				</div>
			</div>
			<!-- 头部搜索 end -->

			<!-- 用户中心 start-->
			<div class="user fl">
				<dl>
					<dt>
						<em></em>
						<a href="">用户中心</a>
						<b></b>
					</dt>
					<dd>
						<div class="prompt">
                            <?php if (Yii::$app->user->isGuest){
                                echo"您好，请<a href=''>登录</a>";
                            }else{
                                echo "您好，欢迎来到水果之家[".Yii::$app->user->identity->username."]";
                            }
                            ?>

						</div>
						<div class="uclist mt10">
							<ul class="list1 fl">
								<li><a href="">用户信息></a></li>
								<li><a href="">我的订单></a></li>
								<li><a href="">收货地址></a></li>
								<li><a href="">我的收藏></a></li>
							</ul>

							<ul class="fl">
								<li><a href="">我的留言></a></li>
								<li><a href="">我的红包></a></li>
								<li><a href="">我的评论></a></li>
								<li><a href="">资金管理></a></li>
							</ul>

						</div>
						<div style="clear:both;"></div>
						<div class="viewlist mt10">
							<h3>最近浏览的商品：</h3>
							<ul>
								<li><a href=""><img src="/images/view_list1.jpg" alt="" /></a></li>
								<li><a href=""><img src="/images/view_list2.jpg" alt="" /></a></li>
								<li><a href=""><img src="/images/view_list3.jpg" alt="" /></a></li>
							</ul>
						</div>
					</dd>
				</dl>
			</div>
			<!-- 用户中心 end-->

			<!-- 购物车 start -->
			<div class="cart fl">
				<dl>
					<dt>
						<a href="<?=\yii\helpers\Url::to(['cart/index'])?>">去购物车结算</a>
						<b></b>
					</dt>
					<dd>
						<div class="prompt">
							购物车中还没有商品，赶紧选购吧！
						</div>
					</dd>
				</dl>
			</div>
			<!-- 购物车 end -->
		</div>
		<!-- 头部上半部分 end -->
		
		<div style="clear:both;"></div>

		<!-- 导航条部分 start -->
        <div class="nav w1210 bc mt10">
            <!--  商品分类部分 start-->
            <div class="category fl <?php if(\Yii::$app->request->getPathInfo()){ echo 'cat1';}?>"> <!-- 非首页，需要添加cat1类 -->
                <div class="cat_hd <?php if(\Yii::$app->request->getPathInfo()){ echo 'off';}else{echo 'on';}?>">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
                    <h2>全部商品分类</h2>
                    <em></em>
                </div>
                <div class="cat_bd" <?php if(\Yii::$app->request->getPathInfo()){ echo "style='display:none;'";}?>>
                    <?=\frontend\controllers\GoodsListController::getcategory()?>
                </div>
            </div>
			<!--  商品分类部分 end--> 

			<div class="navitems fl">
				<ul class="fl">
					<li class="current"><a href="">首页</a></li>
					<li><a href="">电脑频道</a></li>
					<li><a href="">家用电器</a></li>
					<li><a href="">品牌大全</a></li>
					<li><a href="">团购</a></li>
					<li><a href="">积分商城</a></li>
					<li><a href="">夺宝奇兵</a></li>
				</ul>
				<div class="right_corner fl"></div>
			</div>
		</div>
		<!-- 导航条部分 end -->
	</div>
	<!-- 头部 end-->
	
	<div style="clear:both;"></div>
    <script type="text/javascript">
        $('.btn').prop('disabled','disabled');
        $('.txt').on('keyup',function () {
            var v=$(this).val();
            if(v){
                $('.btn').prop('disabled','');
            }else {
                $('.btn').prop('disabled','disabled');
            }
        })
    </script>
<?= $content ?>
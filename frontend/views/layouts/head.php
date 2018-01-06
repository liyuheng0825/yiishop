<?php
/* @var $content string */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <title>水果之家</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/index.css" type="text/css">
    <link rel="stylesheet" href="/style/bottomnav.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
    <link rel="stylesheet" href="/style/home.css" type="text/css">
    <link rel="stylesheet" href="/style/address.css" type="text/css">
    <link rel="stylesheet" href="/style/goods.css" type="text/css">
    <link rel="stylesheet" href="/style/common.css" type="text/css">
    <link rel="stylesheet" href="/style/list.css" type="text/css">
    <link rel="stylesheet" href="/style/login.css" type="text/css">
    <link rel="stylesheet" href="/style/cart.css" type="text/css">



    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/header.js"></script>
    <script type="text/javascript" src="/js/index.js"></script>
    <script type="text/javascript" src="/js/jsAddresss.js"></script>
    <script type="text/javascript" src="/js/home.js"></script>
    <script type="text/javascript" src="/js/goods.js"></script>
    <script type="text/javascript" src="/js/jqzoom-core.js"></script>
    <script type="text/javascript" src="/js/list.js"></script>
    <script type="text/javascript" src="/js/cart1.js"></script>
    <script type="text/javascript" src="/js/cart2.js"></script>
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
                        <li><a href="'.\yii\helpers\Url::to(['member/address']).'">我的订单</a></li>';
                    }
                    ?>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->
<?= $content ?>

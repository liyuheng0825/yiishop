<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '水果之家后台系统',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);



    /*$menuItems = [
        [
            'label'=>'文章',
            'items'=>[
                ['label' => '文章分类', 'url' =>['/article-category/index']
                ],
                ['label' => '文章列表', 'url' => ['/article/index']
                ],
            ]
        ],
        [
            'label'=>'商品',
            'items'=>[
                ['label' => '商品品牌列表', 'url' =>['/brand/index']],

                ['label' => '商品分类列表', 'url' => ['/goods-category/index']],

                ['label' => '商品列表', 'url' => ['/goods/index']],
            ]
        ],[
            'label'=>'RBAC权限',
            'items'=>[
                ['label' => '权限列表', 'url' =>['/rbac/index']],

                ['label' => '角色列表', 'url' => ['/rbac/index-role']],
            ]
        ],
        [
            'label'=>'菜单管理',
            'items'=>[
                ['label' => '菜单列表', 'url' =>['/menu/index']],
            ]
        ],
        [
            'label'=>'管理员中心',
            'items'=>[
                ['label' => '管理员列表', 'url' =>['/user/index']],
                ['label' => '修改密码', 'url' =>['/user/edit-password']],

                //['label' => '商品分类列表', 'url' => ['/goods-category/index']],

                //['label' => '商品列表', 'url' => ['/goods/index']],
            ]
        ],

        //['label' => '商品品牌列表', 'url' => ['/brand/index']],
        //['label' => '文章分类管理列表', 'url' => ['/article-category/index']],
        //['label' => '文章列表', 'url' => ['/article/index']],
        //['label' => '商品分类列表', 'url' => ['/goods-category/index']],
        //['label' => '商品列表', 'url' => ['/goods/index']],
        //['label' => '管理员列表', 'url' => ['/user/index']],
    ];*/

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/user/login']];
    } else {
        $menuItems = \Yii::$app->user->identity->Menus();//>>调用user里面的菜单
        $menuItems[] = '<li>'
            . Html::beginForm(['/user/logout'], 'post')
            . Html::submitButton(
                '注销 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

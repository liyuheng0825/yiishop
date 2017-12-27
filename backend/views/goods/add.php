<h1>商品添加</h1>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//>>名字
echo $form->field($model,'sn')->textInput(['readonly'=>"value"]);//>>货号
//echo $form->field($model,'logo')->fileInput();//>>图片
if($model->logo){
    echo "<img src='{$model->logo}' alt='logo图片' width='200' height='200' id='myimg'>";
}
echo $form->field($model,'logo')->hiddenInput()->label('logo图片');//>>隐藏域 由JS直接添加值
//------------webuploader-----------------
//>>注册插件css和js
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    //>>指定该文件在jQuery后加载
]);
echo
<<<HTML
<img id="myimg">
<div id="uploader" class="wu-example" >
<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
HTML;
//>>定义接受的方法路径
$upload_url = \yii\helpers\Url::to(['goods/uploader']);
$js =
    <<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/webuploader/Uploader.swf',

    // 文件接收服务端。
    server: '{$upload_url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
    }
});

//>>回显图片 监听事件
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file,response ) {
    //>>回显图片
    $('#myimg').attr('src',response.url);
    //>>赋值给隐藏域
    $('#goods-logo').val(response.url);
});
JS;
//注册js
$this->registerJs($js);
//----------------------------------------
echo $form->field($model,'goods_category_id')->hiddenInput( );//>>商品分类
//=====================zTree===========================
//>>加载zTree需要的css和js文件注册
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    //>>指定该js文件依赖于jquery(在jquery文件加载之后)
    'depends'=>\yii\web\JqueryAsset::className()
]);
//>>输出HTML
echo <<<HTML
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;
//>>输出JS
$nodes=\backend\models\GoodsCategory::getNodes();//>>JSON对象
$id = $model->goods_category_id;
if (!$id){
    $id=0;
}
$js = <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
		            onClick: function(event, treeId, treeNode) {
		              //>>节点被点击 该节点父级ID赋值给$('#goodscategory-parent_id')
		              $('#goods-goods_category_id').val(treeNode.id);
		            }
		           
	                    }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //>>展开所有节点
            zTreeObj.expandAll(true);
            //>>获取该节点
                var node = zTreeObj.getNodeByParam('id',{$id},null);  
            //>>节点回显
            zTreeObj.selectNode(node);
        
JS;
$this->registerJs($js);
//=====================================================
echo $form->field($model,'brand_id')->dropDownList($article);//>>品牌分类
echo $form->field($model,'market_price')->textInput();//>>市场价格
echo $form->field($model,'shop_price')->textInput();//>>商品价格
echo $form->field($model,'stock')->textInput();//>>库存
echo $form->field($model,'is_on_sale')->dropDownList(['1'=>'在售','0'=>'下架']);//>>是否在售
echo $form->field($model,'sort')->textInput();//>>排序
//echo $form->field($model,'status')->dropDownList(['0'=>'隐藏','1'=>'正常']);//>>状态
echo $form->field($goods_intro,'content')->widget('kucha\ueditor\UEditor',[]);//商品详情
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();

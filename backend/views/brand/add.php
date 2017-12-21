<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//>>名字
echo $form->field($model,'intro')->textarea();//>>简介
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
$upload_url = \yii\helpers\Url::to(['brand/upload']);
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
    $('#brand-logo').val(response.url);
});
JS;
//注册js
$this->registerJs($js);


//----------------------------------------
echo $form->field($model,'sort')->textInput();//>>排序
echo $form->field($model,'status')->dropDownList(['0'=>'隐藏','1'=>'正常']);//>>状态
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();

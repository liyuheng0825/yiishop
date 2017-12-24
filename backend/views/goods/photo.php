<?php
//------------webuploader-----------------
//>>注册插件css和js
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    //>>指定该文件在jQuery后加载
]);
echo
<<<HTML

<div id="uploader" class="wu-example" >
<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
HTML;
//>>定义接受的方法路径
$upload_url = \yii\helpers\Url::to(['goods/add-photo','id'=>$id]);
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
    var html ='<tr>'+
                 '<td>'+response.goods_id+'</td>'+
                 '<td><img src='+response.url+' width="60" height="60"></td>'+
                 '<td><a href="#" class="btn btn-default" role="button" value='+response.id+'>删除</a></td>'
                  +'</tr>';
       $(".table").append(html);
});
JS;
//注册js
$this->registerJs($js);
?>
<table class="table">
    <tr>
        <td>图片</td>
        <td>操作</td>
    </tr>
        <?php foreach ($rows as $row):?>
        <tr>
            <td><img src="<?=$row['path']?>" alt="" width="60" height="60"></td>
            <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row['id']?>">删除</a></td>
        </tr>
    <?php endforeach;?>


</table>
<script type="text/javascript">
    $("table").on('click','tr td #delete',function () {
        var result = confirm("确定删除吗?");
        if (result==true){
            //获取id
            var id = $(this).attr('value');
            //无刷新删除
            $(this).parents("tr").remove();
            $.post('photo-delete',{"id":id},function (result) {
                if(result>0){
                    alert('删除成功');
                }else {
                    alert('删除失败')
                }
            });
        }
    })
</script>
<?php
$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css');//>>注册css
$this->registerJsFile('http://code.jquery.com/jquery-1.10.2.min.js');//>>注册jquery
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js');//>>注册js
?>


<h1>权限列表</h1>
<a href="<?=\yii\helpers\Url::to(['rbac/add'])?>" class="btn btn-info" role="button">添加</a>
    <div id="container">
    <table id="example" class="table table-striped table-bordered">
    <thead>
    <tr>
        <td>路由/名称</td>
        <td>描述</td>
        <td>操作</td>
    </tr>
        </thead>
        <tbody>
    <?php foreach ($rows as $row):?>
        <tr>
            <td><?=$row->name?></td>
            <td><?=$row->description?></td>
            <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row->name?>">删除</a><a href="<?=\yii\helpers\Url::to(['rbac/edit','name'=>$row->name])?>"  class="btn btn-default" role="button">修改</a></td>
        </tr>
    <?php endforeach;?>
        </tbody>
</table>
    </div>
<?php
$js =
    <<<JS
    $("table").on('click','tr td #delete',function () {
        var result = confirm("确定删除吗?");
        if (result==true){
            //获取id
            var name = $(this).attr('value');
            //无刷新删除
           $(this).parents("tr").remove();
            $.post('delete',{"name":name},function (result) {
                if(result>0){
                   alert('删除成功');
               }else {
                   alert('删除失败');
               }
            });      
        }
    });
//==============================

$('#example').dataTable({
"oLanguage": {
"sLengthMenu": "每页显示 _MENU_ 条记录",
"sZeroRecords": "对不起，查询不到任何相关数据",
"sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_条记录",
"sInfoEmtpy": "找不到相关数据",
"sInfoFiltered": "数据表中共为 _MAX_ 条记录)",
"sProcessing": "正在加载中...",
"sSearch": "搜索",
"oPaginate": {
"sFirst": "第一页",
"sPrevious":" 上一页 ",
"sNext": " 下一页 ",
"sLast": " 最后一页 "
}
}
});
JS;
$this->registerJs($js);

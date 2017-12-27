<h1>角色/身份列表</h1>

<table class="table">
    <tr>
        <td>角色/身份</td>
        <td>描述</td>
        <td>操作</td>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr>
            <td><?=$row->name?></td>
            <td><?=$row->description?></td>
            <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row->name?>">删除</a><a href="<?=\yii\helpers\Url::to(['rbac/edit-role','name'=>$row->name])?>"  class="btn btn-default" role="button">修改</a></td>
        </tr>
    <?php endforeach;?>
</table>
<a href="<?=\yii\helpers\Url::to(['rbac/add-role'])?>" class="btn btn-danger btn-lg btn-block" role="button">添加</a>
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
            $.post('delete-role',{"name":name},function (result) {
                if(result>0){
                   alert('删除成功');
               }else {
                   alert('删除失败');
               }
            });      
        }
    })
JS;
$this->registerJs($js);

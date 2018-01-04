<h1>菜单列表</h1>
<table class="table">
    <tr>
        <td>菜单名称</td>
        <td>路由/地址</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr>
            <td><?=$row['name']?></td>
            <td><?=$row['route']?></td>
            <td><?=$row['sort']?></td>
            <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row['id']?>">删除</a><a href="<?=\yii\helpers\Url::to(['menu/edit','id'=>$row['id']])?>"  class="btn btn-default" role="button">修改</a></td>
        </tr>
    <?php endforeach;?>
</table>
<a href="<?=\yii\helpers\Url::to(['menu/add'])?>" class="btn btn-info btn-lg btn-block" role="button">添加</a>
<?php
$js =
    <<<JS
    $("table").on('click','tr td #delete',function () {
        var result = confirm("确定删除吗?");
        if (result==true){
            //获取id
            var id = $(this).attr('value');
            //无刷新删除
           $(this).parents("tr").remove();
            $.post('delete',{"id":id},function (result) {
                if(result>0){
                   alert('删除成功');
               }else {
                   alert('删除失败,该菜单有子级菜单');
               }
            });      
        }
    })
JS;
$this->registerJs($js);

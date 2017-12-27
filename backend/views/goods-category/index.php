<h1>商品分类列表</h1>
<a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-info" role="button">添加</a>
<table class="table">
    <tr>
        <td>序号</td>
        <td>商品分类名称</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['name']?></td>
            <td><?=$row['intro']?></td>
            <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row['id']?>">删除</a><a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$row['id']])?>"  class="btn btn-default" role="button">修改</a></td>
        </tr>
    <?php endforeach;?>
</table>
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
                   alert('删除失败,有节点分类');
                   top.location.reload();
               }
            });      
        }
    })
JS;
$this->registerJs($js);

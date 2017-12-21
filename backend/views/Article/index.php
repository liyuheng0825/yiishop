<a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-info" role="button">添加</a>
<table class="table">
    <tr>
        <td>序号</td>
        <td>文章名称</td>
        <td>简介</td>
        <td>文章分类</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><?=$row->article_category->name?></td>
            <td><?php if($row->status==1){
                    echo '正常';
                }
                if ($row->status==0){
                    echo '隐藏';
                }
                ?></td>
            <td><?=date('Y-m-d H:i:s',$row->create_time)?></td>
            <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row['id']?>">删除</a><a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$row->id])?>"  class="btn btn-default" role="button">修改</a></td>
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
                   alert('删除失败')
               }
            });
        }
    })
JS;
$this->registerJs($js);


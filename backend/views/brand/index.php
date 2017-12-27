<h1>商品品牌列表</h1>
<a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-info" role="button">添加</a>
<table class="table">
    <tr>
        <td>序号</td>
        <td>名称</td>
        <td>简介</td>
        <td>LOGO图片</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['name']?></td>
            <td><?=$row['intro']?></td>
            <td><img src="<?=$row['logo']?>" alt="" width="60" height="60"></td>
            <td><?php if($row['status']==1){
                echo '正常';
                }
                if ($row['status']==0){
                    echo '隐藏';
                }
                if ($row['status']==-1){
                    echo '删除';
                }
                ?></td>

            <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row['id']?>">删除</a><a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$row['id']])?>"  class="btn btn-default" role="button">修改</a></td>
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
           $.post('delete',{"id":id},function (result) {
               if(result>0){
                   alert('删除成功');
               }else {
                   alert('删除失败')
               }
           });


        }
    })



</script>
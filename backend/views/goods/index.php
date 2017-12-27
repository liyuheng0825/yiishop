<h1>商品列表</h1>

<form action="" method="post">
    <input type="text" name="name" placeholder="商品名称" class="btn">
    <input type="text" name="sn" placeholder="货号" class="btn">
    <input type="text" name="market_price" placeholder="市场价格" class="btn">
    <input type="text" name="shop_price" placeholder="商品价格" class="btn">
    <input type="submit" class="btn btn-info" value="搜索">
</form>
<table class="table">
    <tr>
        <td>序号</td>
        <td>商品名称</td>
        <td>货号</td>
        <td>LOGO图片</td>
        <td>商品分类</td>
        <td>品牌分类</td>
        <td>市场价格</td>
        <td>商品价格</td>
        <td>库存</td>
        <td>是否在售</td>
        <td>状态</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    <?php foreach ($rows as $row):?>
        <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['name']?></td>
            <td><?=$row['sn']?></td>
            <td><img src="<?=$row['logo']?>" alt="" width="60" height="60"></td>
            <td><?=$row->goods_category->name?></td>
            <td><?=$row->brand->name?></td>
            <td><?=$row['market_price']?></td>
            <td><?=$row['shop_price']?></td>
            <td><?=$row['stock']?></td>
            <td><?=$row['is_on_sale']==1?'在售':'下架'?></td>
            <td><?=$row['status']==1?'正常':'回收站'?></td>
            <td><?=$row['sort']?></td>
            <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row['id']?>">删除</a><a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$row['id']])?>"  class="btn btn-default" role="button">修改</a>
                <a href="<?=\yii\helpers\Url::to(['goods/photo','id'=>$row['id']])?>"  class="btn btn-default" role="button">商品图片</a></td>
        </tr>
    <?php endforeach;?>
</table>
<a href="<?=\yii\helpers\Url::to(['goods/add'])?>" class="btn btn-info btn-lg btn-block" role="button">添加</a>
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
<?=\yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
])?>

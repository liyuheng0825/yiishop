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
            <td><?=$row->article_category_id?></td>
            <td><?php if($row->status==1){
                    echo '正常';
                }
                if ($row->status==0){
                    echo '隐藏';
                }
                ?></td>
            <td><?=$row->create_time?></td>
            <td><a href="<?=\yii\helpers\Url::to(['article/delete','id'=>$row->id])?>"  class="btn btn-default" role="button">删除</a><a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$row->id])?>"  class="btn btn-default" role="button">修改</a></td>
        </tr>
    <?php endforeach;?>
</table>


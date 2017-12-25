
<a href="<?=\yii\helpers\Url::to(['user/add'])?>" class="btn btn-info" role="button">添加</a>
<table class="table">
    <tr>
        <td>序号</td>
        <td>用户名</td>
        <td>邮箱</td>
        <td>状态</td>
        <td>注册时间</td>
        <td>更新时间</td>
        <td>最后登陆时间</td>
        <td>最后登录ip</td>
        <td>操作</td>
    </tr>
    <?php foreach ($rows as $row):?>
    <tr>
        <td><?=$row->id?></td>
        <td><?=$row->username?></td>
        <td><?=$row->email?></td>
        <td><?=$row->status==1?'正常登录':'不可登录'?></td>
        <td><?=date('Y-m-d H:i',$row->created_at)?></td>
        <td><?=date('Y-m-d H:i',$row->updated_at)?></td>
        <td><?=date('Y-m-d H:i',$row->last_login_time)?></td>
        <td><?=$row->last_login_ip?></td>
        <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row['id']?>">删除</a><a href="<?=\yii\helpers\Url::to(['user/edit','id'=>$row->id])?>"  class="btn btn-default" role="button">修改</a></td>
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
<h1>管理员列表</h1>

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
        <td id="myspan"><?=$row->status==1?'正常':'禁用'?></td>
        <td><?=date('Y-m-d H:i',$row->created_at)?></td>
        <td><?=date('Y-m-d H:i',$row->updated_at)?></td>
        <td><?=date('Y-m-d H:i',$row->last_login_time)?></td>
        <td><?=$row->last_login_ip?></td>
        <td><a href="#"  class="btn btn-default" role="button" id="delete" value="<?=$row['id']?>"><?=$row->status==1?'禁用':'恢复'?></a>
            <a href="<?=\yii\helpers\Url::to(['user/edit','id'=>$row->id])?>"  class="btn btn-default" role="button">权限操作</a>
            <a href="#"  class="btn btn-default" role="button"  id="reset" value="<?=$row['id']?>">重置密码</a>
        </td>
    </tr>
<?php endforeach;?>
</table>
<a href="<?=\yii\helpers\Url::to(['user/add'])?>" class="btn btn-info btn-lg btn-block" role="button">添加</a>
<?php
$js =
    <<<JS
    //>>删除
    
        $("table").on('click','tr td #delete',function () {
        var result = confirm("禁用后无法登陆");
        if (result==true){
            //获取id
            var id = $(this).attr('value');
            //无刷新修改
            //$(this).html('禁用');
            //$(this).parent("#myspan").html("啊啊");
            $.post('delete',{"id":id},function (result) {
                if(result>0){
                    window.location.reload();
               }else {
                   alert('禁止失败')
               }
            });
        }
    });
    //>>重置密码
     $(".table").on('click','tr td #reset',function () {
        var result = confirm("确定重置密码吗?");
        if (result==true){
            //获取id
            var id = $(this).attr('value');
            $.post('reset-password',{"id":id},function (result) {
                if(result>0){
                   alert('密码已重置成功,请联系高级管理员');
               }else {
                   alert('重置失败')
               }
            });
        }
    })
JS;
$this->registerJs($js);
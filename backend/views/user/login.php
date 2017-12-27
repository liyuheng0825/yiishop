<?php
echo "<h1>管理员登录</h1>";
$html = \yii\bootstrap\ActiveForm::begin();
echo $html->field($model,'username')->textInput();//用户名
echo $html->field($model,'password')->passwordInput();//密码
echo $html->field($model,'auto')->checkbox();//自动登录
echo $html->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'user/captcha',
    'template'=>'<div class="row"><div class="col-xs-1">{input}</div><div class="col-xs-1">{image}</div></div>'
]);
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();

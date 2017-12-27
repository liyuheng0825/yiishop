<?php
echo '<h1>修改密码</h1>';
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'password_a')->passwordInput();
echo $form->field($model,'password_b')->passwordInput();
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();
<?php
echo '<h1>用户角色/身份修改</h1>';
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'role',['inline'=>1])->checkBoxList($role);
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();
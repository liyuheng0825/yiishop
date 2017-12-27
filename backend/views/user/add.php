<?php
echo '<h1>添加管理员</h1>';
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->passwordInput();
//echo $form->field($model,'password_reset_token')->passwordInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status')->dropDownList(['1'=>'正常登录','0'=>'不可登录']);//>>状态
echo $form->field($model,'role',['inline'=>1])->checkBoxList($role);
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();

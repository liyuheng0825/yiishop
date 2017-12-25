<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'password_a')->passwordInput();
echo $form->field($model,'password_b')->passwordInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status')->dropDownList(['1'=>'正常登录','0'=>'不可登录']);//>>状态
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();

<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//>>名字
echo $form->field($model,'intro')->textarea();//>>简介
echo $form->field($model,'logo')->fileInput()->label('logo图片');
echo $form->field($model,'sort')->textInput();//>>排序
echo $form->field($model,'status')->dropDownList(['0'=>'隐藏','1'=>'正常']);//>>状态
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();

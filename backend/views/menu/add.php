<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'previous_menu')->dropDownList($menu);
echo $form->field($model,'route')->dropDownList($permission);
echo $form->field($model,'sort')->textInput();//>>排序
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

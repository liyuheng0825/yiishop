<?php
echo "<h1>添加权限</h1>";
$html = \yii\bootstrap\ActiveForm::begin();
echo $html->field($model,'name')->textInput();
echo $html->field($model,'description')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
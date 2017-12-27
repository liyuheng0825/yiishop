<?php
echo "<h1>添加角色</h1>";
$html = \yii\bootstrap\ActiveForm::begin();
echo $html->field($model,'name')->textInput();
echo $html->field($model,'description')->textInput();
echo $html->field($model,'permission')->checkboxList($permission);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
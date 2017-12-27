<?php
echo "<h1>$liyuheng</h1>";
$html = \yii\bootstrap\ActiveForm::begin();
echo $html->field($model,'name')->textInput();
echo $html->field($model,'description')->textInput();
echo $html->field($model,'permission',['inline'=>1])->checkBoxList($permission);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
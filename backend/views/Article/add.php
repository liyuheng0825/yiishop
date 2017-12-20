<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//>>文章名称
echo $form->field($model,'intro')->textarea();//>>简介
echo $form->field($model,'article_category_id')->dropDownList($articlecategor_id);//文章分类
echo $form->field($model,'sort')->textInput();//>>排序
echo $form->field($model,'status')->dropDownList(['0'=>'隐藏','1'=>'正常']);//>>状态
//echo $form->field($model, 'content')->widget('article\FileInput', []);
echo $form->field($article_detail,'content')->textarea();//文章详情
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();

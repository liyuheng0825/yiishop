<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//>>名字
echo $form->field($model,'sn')->textInput();//>>货号
echo $form->field($model,'logo')->fileInput();//>>图片
echo $form->field($model,'goods_category_id')->dropDownList(['0'=>'隐藏','1'=>'正常']);//>>商品分类
echo $form->field($model,'brand_id')->dropDownList(['0'=>'隐藏','1'=>'正常']);//>>品牌分类
echo $form->field($model,'market_price')->textarea();//>>市场价格
echo $form->field($model,'shop_price')->textarea();//>>商品价格
echo $form->field($model,'stock')->textarea();//>>库存
echo $form->field($model,'status')->dropDownList(['0'=>'下架','1'=>'在售']);//>>是否在售
echo $form->field($model,'status')->dropDownList(['0'=>'回收站','1'=>'正常']);//>>状态
echo $form->field($model,'sort')->textInput();//>>排序
echo $form->field($model,'status')->dropDownList(['0'=>'隐藏','1'=>'正常']);//>>状态
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();

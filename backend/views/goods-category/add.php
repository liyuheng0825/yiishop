<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//>>名字
echo $form->field($model,'parent_id')->hiddenInput();//>>父级ID
//=====================zTree===========================
//>>加载zTree需要的css和js文件注册
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    //>>指定该js文件依赖于jquery(在jquery文件加载之后)
    'depends'=>\yii\web\JqueryAsset::className()
]);
//>>输出HTML
echo <<<HTML
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;
//>>输出JS
$nodes=\backend\models\GoodsCategory::getNodes();//>>JSON对象
$id = $model->id;
if (!$id){
    $id=0;
}
$js = <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
		            onClick: function(event, treeId, treeNode) {
		              //>>节点被点击 该节点父级ID赋值给$('#goodscategory-parent_id')
		              $('#goodscategory-parent_id').val(treeNode.id);
		            }
		           
	                    }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //>>展开所有节点
            zTreeObj.expandAll(true);
            //>>获取该节点
                var node = zTreeObj.getNodeByParam('id',{$id},null);  
            //>>节点回显
            zTreeObj.selectNode(node);
        
JS;
$this->registerJs($js);
//=====================================================
echo $form->field($model,'intro')->textarea();//>>简介
echo '<button class="btn btn-primary" type="submit">提交</button>';
\yii\bootstrap\ActiveForm::end();

<?php
namespace backend\models;
use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * 商品分类模型
 * Class CreateGoods
 * @package backend\models
 */
class GoodsCategory extends ActiveRecord{
    /**
     * 表单验证规则
     */
    public function rules(){
        return[
            //>>字段规则
            [['name','intro','parent_id'],'required'],
            ['parent_id','parentPid'],
        ];
    }

    //>>自定义验证规则
    public function parentPid(){
        //>>查询出父节点数据
        $parend = GoodsCategory::findOne(['id' => $this->parent_id]);
        //>>如果不是一个对象 不验证
       if (!is_object($parend)){
           return false;
       }else{
        //>>判断是否是他的子节点
        if ($parend->isChildOf($this)){
            //>>只处理验证不通过的情况
            $this->addError('parent_id','不能修改子孙节点的子节点');
        }
       }
    }
    public function attributeLabels()
    {
        return [
            'name'=>'品牌名称',
            'intro'=>'简介',
            'parent_id'=>'选择父级'
        ];
    }
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',//打开
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new Category(get_called_class());
    }
    /**
     * 获取分类数据,作为ztree的节点数据
     */
    public static function getNodes(){
        //>>asArray()把对象转成数组
        $nodes = self::find()->select(['id','parent_id','name'])->asArray()->all();
        //>>定义顶级分类
        $nodes[] = ['id'=>0,'parent_id'=>0,'name'=>'【顶级分类】'];
        return json_encode($nodes);
    }
    /**
     * 获取所有数据
     * @param  $parent_id
     * 父分类id
     */
    public function getAll($parent_id = 0){
        $categorys = self::find()->all();
        //排序和缩进
        $categoryResult = $this->getChildren($categorys,$parent_id,0);
        //返回分类数据
        return $categoryResult;
    }
    /**
     * 找儿子的方法
     * @param $categorys
     * 所有的分类数据
     * @param $parent_id
     * 父分类id
     * @param $deep
     * 层级
     */
    private function getChildren(&$categorys,$parent_id,$deep=0){
        //准备一个空数组,用于装找到的儿子
        static $children = [];
        //循环所有的分类数据,将每一条数据中的parent_id进行必须,
        //等于我传入的$parent_id,就是我们需要的儿子
        foreach ($categorys as $category){
            if($category->parent_id == $parent_id){
                //在每一个找到的儿子上保存一个字段表示缩进好的分类名称
                $category->name = str_repeat("---",$deep*2).$category->name;
                $children[] = $category;
                //继续找,$category下可能还有子节点
                $this->getChildren($categorys,$category->id,$deep+1);
            }
        }
        //返回儿子
        return $children;
    }
}

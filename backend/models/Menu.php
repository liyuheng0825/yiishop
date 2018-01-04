<?php
namespace backend\models;
use yii\db\ActiveRecord;

/**
 * 菜单模型
 * Class Menu
 * @package backend\models
 */
class Menu extends ActiveRecord{
    /**
     * 字段名
     * @return array
     */
    public function attributeLabels()
    {
        return [
          'id'=>'序号',
            'name'=>'菜单名称',
            'previous_menu'=>'上级菜单',
            'route'=>'路由/地址',
            'sort'=>'排序'
        ];
    }

    /**
     * 验证规则
     * @return array
     */
    public function rules()
    {
        return [
          [['name','previous_menu','route','sort'],'required'],
        ];
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
            if($category->previous_menu == $parent_id){
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

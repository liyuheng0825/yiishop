<?php
namespace backend\models;
use yii\db\ActiveRecord;

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
            [['name','intro'],'required'],

        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'品牌名称',
            'intro'=>'简介',
        ];
    }
}

<?php
namespace backend\models;
use yii\db\ActiveRecord;

/**
 * 文章分类模型
 * Class ArticleCategory
 * @package backend\models
 */
class ArticleCategory extends ActiveRecord{
    /**
     * 表单验证规则
     */
    public function rules(){
        return[
            //>>字段规则
            [['name','intro','status','sort'],'required'],

        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'品牌名称',
            'intro'=>'简介',
            'status'=>'状态',
            'sort'=>'排序'
        ];
    }
}

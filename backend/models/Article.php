<?php
namespace backend\models;
use yii\db\ActiveRecord;

/**
 * 文章管理模型
 * Class Article
 * @package backend\models
 */
class Article extends ActiveRecord{
    /**
     * 表单验证规则
     */
    public function rules(){
        return[
            //>>字段规则
            [['name','intro','status','article_category_id','sort'],'required'],

        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'文章表题',
            'intro'=>'文章简介',
            'status'=>'状态',
            'article_category_id'=>'分类',
            'sort'=>'排序'
        ];
    }
    public function getStudent(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
}

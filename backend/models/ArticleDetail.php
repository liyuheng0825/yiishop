<?php
namespace backend\models;
use yii\db\ActiveRecord;

/**
 * 文章详情
 * Class ArticleDetail
 * @package backend\models
 */
class ArticleDetail extends ActiveRecord{
    /**
     * 表单验证规则
     */
    public function rules(){
        return[
            //>>字段规则
            [['content'],'required'],

        ];
    }
    public function attributeLabels()
    {
        return [
            'content'=>'文章详情',
        ];
    }
}

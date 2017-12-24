<?php
namespace backend\models;
use yii\db\ActiveRecord;

/**
 * 商品详情表
 * Class GoodsIntro
 * @package backend\models
 */
class GoodsIntro extends ActiveRecord{
    /**
     * 表单验证规则
     */
    public function rules(){
        return[
            //>>字段规则
            [['content'],'required'],
            //>>id 存在
            ['goods_id','exist'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'content'=>'商品详情',
        ];
    }
}

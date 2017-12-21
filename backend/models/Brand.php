<?php
namespace backend\models;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * 品牌模型
 * Class Brand
 * @package frontend\models
 */
class Brand extends ActiveRecord {

    /**
     * 表单验证规则
     */
    public function rules(){
        return[

            //>>字段规则
            [['name','intro','status','sort'],'required'],
            //>>图片默认值为空
            ['logo', 'default', 'value' => null],
            ['sort', 'integer'],

        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'品牌名称',
          'intro'=>'简介',
          'logo'=>'LOGO图片',
          'sort'=>'排序',
          'status'=>'状态',
        ];
    }
}

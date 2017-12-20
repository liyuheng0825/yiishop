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
    public $imgFile;
    /**
     * 表单验证规则
     */
    public function rules(){
        return[
            //>>上传文件的验证规则
            ['imgFile','file','extensions'=>['jpg','png','gif'],'maxSize'=>1024*1024],
            //>>字段规则
            [['name','intro','logo','status','sort'],'required'],

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

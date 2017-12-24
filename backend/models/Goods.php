<?php
namespace backend\models;
use yii\db\ActiveRecord;

/**
 * 商品模型
 * Class Goods
 * @package backend\models
 */
class Goods extends ActiveRecord{
    /**
     * 表单验证规则
     */
    public function rules(){
        return[
            //>>字段规则
            [['name','sn','logo','goods_category_id','brand_id','market_price','shop_price','stock','is_on_sale','sort',],'required'],
            //>>整数
            [['market_price','shop_price','stock'], 'number'],
            //>>状态
            ['status', 'default', 'value' => 1],

        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'商品名称',
            'sn'=>'货号',
            'logo'=>'LOGO图片',
            'goods_category_id'=>'商品分类id',
            'brand_id'=>'品牌分类',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否在售',
            //'status'=>'状态 ',
            'sort'=>'排序 ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * 关联商品分类id
     */
    public function getgoods_category(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    /**
     * 关联品牌分类
     */
    public function getbrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
}

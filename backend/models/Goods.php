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
            [['name','sn','logo','goods_category_id','brand_id','market_price','shop_price','stock','is_on_sale','status','sort'],'required'],

        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'品牌名称',
            'sn'=>'货号',
            'logo'=>'LOGO图片',
            'goods_category_id'=>'商品分类id',
            'brand_id'=>'品牌分类',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否在售',
            'status'=>'状态 ',
            'sort'=>'排序 ',
        ];
    }
}

<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class OrderGoods extends ActiveRecord{
    public function rules()
    {
        return [
            //>>必填
            [['order_id','goods_id','goods_name','logo','price','amount','total'],'required'],
        ];
    }
}
<?php
namespace frontend\models;
use yii\db\ActiveRecord;
//>>订单表活动记录
class Order extends ActiveRecord{
    public function rules()
    {
        return [
          [['member_id','name','province','city','area','address','tel','delivery_id','delivery_name','delivery_price','payment_id','payment_name','total','status','create_time'],'required'],
            //>>第三方支付
            ['trade_no','default', 'value' => null]
        ];
    }
}

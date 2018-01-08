<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_payment`.
 */
class m180106_025515_create_order_payment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_payment', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->comment('支付方式'),
            'explain'=>$this->string(20)->comment('说明'),
            'state'=>$this->integer()->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_payment');
    }
}

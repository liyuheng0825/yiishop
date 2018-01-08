<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_expressage`.
 */
class m180106_025153_create_order_expressage_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_expressage', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('送货方式'),
            'freight'=>$this->decimal()->comment('运费'),
            'standard'=>$this->string()->comment('运费标准'),
            'state'=>$this->string()->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_expressage');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `hits`.
 */
class m180104_075726_create_hits_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('hits', [
            'id' => $this->primaryKey(),
            'goods_id' =>$this->integer()->comment('商品id'),
            'hits' =>$this->integer()->comment('点击数')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('hits');
    }
}

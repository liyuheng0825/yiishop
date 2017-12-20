<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m171220_061104_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'logo'=>$this->string(255)->comment('Logo'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->integer(3)->comment('状态(-1删除0隐藏1正常)')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}

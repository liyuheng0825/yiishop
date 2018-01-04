<?php

use yii\db\Migration;

/**
 * Handles the creation of table `useraddress`.
 */
class m180103_020034_create_useraddress_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('useraddress', [
            'id' => $this->primaryKey(),
            'user'=> $this->string(50)->comment('用户名'),
            'recipients'=> $this->string(50)->comment('收件人'),
            'area'=>$this->string(50)->comment('收件地区'),
            'particular'=>$this->string(255)->comment('详细地区'),
            'tel'=>$this->integer()->comment('手机号码'),
            'state'=>$this->integer(1)->comment('状态(1默认选中,0正常)')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('useraddress');
    }
}

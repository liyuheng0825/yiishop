<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171225_052856_create_user_table extends Migration
{
    /**
     * 添加字段
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user','last_login_time',$this->string(30)->comment('最后登录时间'));
        $this->addColumn('user','last_login_ip',$this->string(30)->comment('最后登录ip'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}

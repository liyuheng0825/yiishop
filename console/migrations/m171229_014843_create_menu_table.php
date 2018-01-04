<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171229_014843_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(30)->comment('菜单名称'),
            'previous_menu'=>$this->string(30)->comment('上级菜单'),
            'route'=>$this->string(40)->comment('路由/地址'),
            'sort'=>$this->integer()->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}

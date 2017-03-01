<?php

use yii\db\Migration;

class m170301_091030_ip_list extends Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%ip_list}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'ip' => $this->string(15)->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%ip_list}}');
    }
}

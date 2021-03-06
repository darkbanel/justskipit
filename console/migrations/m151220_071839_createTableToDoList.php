<?php

use yii\db\Schema;
use yii\db\Migration;

class m151220_071839_createTableToDoList extends Migration
{
    public function up()
    {
        $this->createTable('{{%lists}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'ddl' => $this->date(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ],'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('{{%lists}}');
    }
}

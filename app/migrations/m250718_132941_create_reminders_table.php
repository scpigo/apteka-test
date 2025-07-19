<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reminders}}`.
 */
class m250718_132941_create_reminders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reminders}}', [
            'id' => $this->primaryKey(),
            'medicine_id' => $this->integer()->notNull(),
            'time' => $this->json()->notNull(),
            'begin_date' => $this->date()->notNull(),
            'finish_date' => $this->date()->notNull(),
            'comment' => $this->string(255)->notNull(),
            'status' => $this->string(255)->notNull()->defaultValue('new')
        ]);
        $this->addForeignKey('fk_reminders_medicine', 'reminders', 'medicine_id', 'medicines', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reminders}}');
    }
}

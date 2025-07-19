<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%medicines}}`.
 */
class m250718_132925_create_medicines_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%medicines}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'dose' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%medicines}}');
    }
}

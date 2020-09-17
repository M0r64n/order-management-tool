<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m200917_094721_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'name' => $this->string(99)->notNull()->unique(),
            'price' => $this->decimal(12, 2)->notNull(),
            'quantity' => $this->integer()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%products}}');
    }
}

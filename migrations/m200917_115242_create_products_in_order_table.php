<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products_in_order}}`.
 */
class m200917_115242_create_products_in_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products_in_order}}', [
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'quantity' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey(
            'products_in_order_PK',
            '{{%products_in_order}}',
            [
                'product_id',
                'order_id'
            ]
        );

        $this->addForeignKey(
            'product_order_FK',
            '{{%products_in_order}}',
            'order_id',
            '{{%orders}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'order_product_FK',
            '{{%products_in_order}}',
            'product_id',
            '{{%products}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('order_product_FK', '{{%products_in_order}}');
        $this->dropForeignKey('product_order_FK', '{{%products_in_order}}');

        $this->dropTable('{{%products_in_order}}');
    }
}

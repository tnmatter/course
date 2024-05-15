<?php

use yii\db\Migration;

/**
 * Class m240515_203528_all
 */
class m240515_203528_all extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'user',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull(),
                'phone' => $this->string(15)->notNull(),
                'created_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
                'updated_at' => $this->timestamp(0)->notNull()->defaultExpression('now()')
            ]
        );
        $this->createTable(
            'product',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull(),
                'description' => $this->text(),
                'count' => $this->integer()->notNull()->defaultValue(0),
                'avatar' => $this->string(255),
                'created_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
                'updated_at' => $this->timestamp(0)->notNull()->defaultExpression('now()')
            ]
        );
        $this->createTable(
            'order',
            [
                'id' => $this->primaryKey(),
                'customer_name' => $this->string(255)->notNull(),
                'customer_phone' => $this->string(15)->notNull(),
                'address' => $this->text()->notNull(),
                'deliver_from' => $this->timestamp(0)->notNull(),
                'deliver_to' => $this->timestamp(0)->notNull(),
                'courier_id' => $this->integer()->notNull(),
                'created_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
                'updated_at' => $this->timestamp(0)->notNull()->defaultExpression('now()'),
                'status' => $this->string(255)->notNull(),
                'avatar' => $this->string(255),
                'delivered_at' => $this->timestamp(0),
                'feedback' => $this->text(),
                'feedback_assessment' => $this->integer()
            ]
        );
        $this->addForeignKey('order_courier_id_fkey', 'order', ['courier_id'], 'user', ['id'], 'CASCADE', 'CASCADE');
        $this->createIndex('order_courier_id_idx', 'order', ['courier_id']);
        $this->createTable(
            'order_product',
            [
                'id' => $this->primaryKey(),
                'product_id' => $this->integer()->notNull(),
                'order_id' => $this->integer()->notNull(),
                'count' => $this->integer()->notNull()
            ]
        );
        $this->addForeignKey('order_product_product_id_fkey', 'order_product', ['product_id'], 'product', ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('order_product_order_id_fkey', 'order_product', ['order_id'], 'order', ['id'], 'CASCADE', 'CASCADE');
        $this->createIndex('order_order_id_product_id_idx', 'order_product', ['order_id', 'product_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_product');
        $this->dropTable('order');
        $this->dropTable('product');
        $this->dropTable('user');
    }
}

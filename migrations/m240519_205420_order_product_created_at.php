<?php

use yii\db\Migration;

/**
 * Class m240519_205420_order_product_created_at
 */
class m240519_205420_order_product_created_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order_product', 'created_at', $this->timestamp(0)->notNull()->defaultExpression('now()'));
        $this->addColumn('order_product', 'updated_at', $this->timestamp(0)->notNull()->defaultExpression('now()'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order_product', 'created_at');
        $this->dropColumn('order_product', 'updated_at');
    }
}

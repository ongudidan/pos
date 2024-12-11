<?php

use yii\db\Migration;

/**
 * Class m240925_011459_create_app_tables
 */
class m240925_011459_create_app_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create company table
        $this->createTable('{{%company}}', [
            'id' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
        ]);

        // Create user table
        $this->createTable('{{%user}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'company_id' => $this->string()->defaultValue(null),
            'username' => $this->string()->unique(),
            'phone_no' => $this->string()->unique(),
            'verification_token' => $this->string()->defaultValue(null),
            'auth_key' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create RBAC tables
        $this->createTable('{{%auth_rule}}', [
            'name' => $this->string()->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
        ]);

        $this->createTable('{{%auth_item}}', [
            'name' => $this->string()->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
            'KEY rule_name (rule_name)',
            'KEY type (type)',
        ]);

        $this->createTable('{{%auth_item_child}}', [
            'parent' => $this->string()->notNull(),
            'child' => $this->string()->notNull(),
            'PRIMARY KEY (parent, child)',
            'KEY child (child)',
            'FOREIGN KEY ([[parent]]) REFERENCES {{%auth_item}} ([[name]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[child]]) REFERENCES {{%auth_item}} ([[name]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        $this->createTable('{{%auth_assignment}}', [
            'item_name' => $this->string()->notNull(),
            'user_id' => $this->string()->notNull(), // Changed to string
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY ([[item_name]]) REFERENCES {{%auth_item}} ([[name]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[user_id]]) REFERENCES {{%user}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create payment_methods table first
        $this->createTable('{{%payment_method}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'company_id' => $this->string()->defaultValue(null),
            'name' => $this->string()->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),

        ]);

        // Create product_category table
        $this->createTable('{{%product_category}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'company_id' => $this->string()->defaultValue(null),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'thumbnail' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->notNull()->defaultValue(null),
            'updated_at' => $this->integer()->notNull()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),

        ]);

        // Create product sub category table
        $this->createTable('{{%product_sub_category}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'product_category_id' => $this->string()->defaultValue(null),
            'company_id' => $this->string()->defaultValue(null),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'thumbnail' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[product_category_id]]) REFERENCES {{%product_category}} ([[id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create product table
        $this->createTable('{{%product}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'product_sub_category_id' => $this->string()->defaultValue(null),
            'product_category_id' => $this->string()->defaultValue(null),
            'company_id' => $this->string()->defaultValue(null),
            'name' => $this->string()->defaultValue(null),
            'selling_price' => $this->decimal()->defaultValue(null),
            'product_number' => $this->string()->defaultValue(null),
            'description' => $this->string()->defaultValue(null),
            'thumbnail' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[product_sub_category_id]]) REFERENCES {{%product_sub_category}} ([[id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[product_category_id]]) REFERENCES {{%product_category}} ([[id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create expense_category table
        $this->createTable('{{%expense_category}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'company_id' => $this->string()->defaultValue(null),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'thumbnail' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),

        ]);

        // Create expense table
        $this->createTable('{{%expense}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'company_id' => $this->string()->defaultValue(null),
            'reference_no' => $this->string()->defaultValue(null),
            'expense_date' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),

        ]);

        // Create expense_item table
        $this->createTable('{{%expense_item}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'expense_category_id' => $this->string()->defaultValue(null),
            'expense_id' => $this->string()->defaultValue(null),
            'company_id' => $this->string()->defaultValue(null),
            'payment_method_id' => $this->string()->defaultValue(null),
            'amount' => $this->decimal()->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[expense_category_id]]) REFERENCES {{%expense_category}} ([[id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[payment_method_id]]) REFERENCES {{%payment_method}} ([[id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[expense_id]]) REFERENCES {{%expense}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),


        ]);

        // Create supplier table first
        $this->createTable('{{%supplier}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'company_id' => $this->string()->defaultValue(null),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->defaultValue(null),
            'phone_no' => $this->string()->defaultValue(null),
            'company_name' => $this->string()->defaultValue(null),
            'address' => $this->string()->defaultValue(null),
            'city' => $this->string()->defaultValue(null),
            'country' => $this->string()->defaultValue(null),
            'postal_code' => $this->string()->defaultValue(null),
            'state' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create customer table first
        $this->createTable('{{%customer}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'company_id' => $this->string()->defaultValue(null),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->defaultValue(null),
            'phone_no' => $this->string()->defaultValue(null),
            'company_name' => $this->string()->defaultValue(null),
            'address' => $this->string()->defaultValue(null),
            'city' => $this->string()->defaultValue(null),
            'country' => $this->string()->defaultValue(null),
            'postal_code' => $this->string()->defaultValue(null),
            'state' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create purchase table
        $this->createTable('purchase', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'company_id' => $this->string()->defaultValue(null),
            'reference_no' => $this->string()->defaultValue(null),
            'supplier_id' => $this->string()->defaultValue(null),
            'purchase_date' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[supplier_id]]) REFERENCES {{%supplier}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),

        ]);

        // Create purchase_product table
        $this->createTable('{{%purchase_product}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'product_id' => $this->string()->defaultValue(null),
            'company_id' => $this->string()->defaultValue(null),
            'purchase_id' => $this->string()->defaultValue(null),
            'quantity' => $this->integer()->notNull(),
            'buying_price' => $this->decimal()->notNull(),
            'total_cost' => $this->decimal()->notNull(),
            'purchase_date' => $this->integer()->notNull(),
            'payment_method_id' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[payment_method_id]]) REFERENCES {{%payment_method}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[purchase_id]]) REFERENCES {{%purchase}} ([[id]]) ' .
            $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        // Create sale table
        $this->createTable('sale', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'customer_id' => $this->string()->defaultValue(null),
            'company_id' => $this->string()->defaultValue(null),
            'reference_no' => $this->string()->defaultValue(null),
            'sale_date' => $this->integer()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[customer_id]]) REFERENCES {{%customer}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),

        ]);

        // Create sale_product table
        $this->createTable('{{%sale_product}}', [
            'id' => $this->string()->notNull()->unique(), // Custom string ID
            'product_id' => $this->string()->defaultValue(null),
            'company_id' => $this->string()->defaultValue(null),
            'sale_id' => $this->string()->defaultValue(null),
            'quantity' => $this->integer()->notNull(),
            'selling_price' => $this->decimal()->notNull(),
            'total_cost' => $this->decimal()->notNull(),
            'payment_method_id' => $this->string()->defaultValue(null),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
            'created_by' => $this->string()->defaultValue(null),
            'updated_by' => $this->string()->defaultValue(null),
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]])' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[company_id]]) REFERENCES {{%company}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[payment_method_id]]) REFERENCES {{%payment_method}} ([[id]]) ' .
                $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[sale_id]]) REFERENCES {{%sale}} ([[id]]) ' .
            $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sale_product}}');
        $this->dropTable('{{%sale}}');
        $this->dropTable('{{%purchase_product}}');
        $this->dropTable('{{%purchase}}');
        $this->dropTable('{{%customer}}');
        $this->dropTable('{{%supplier}}');
        $this->dropTable('{{%expense_item}}');
        $this->dropTable('{{%expense}}');
        $this->dropTable('{{%expense_category}}');
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%product_sub_category}}');
        $this->dropTable('{{%product_category}}');
        $this->dropTable('{{%payment_method}}');
        $this->dropTable('{{%auth_assignment}}');
        $this->dropTable('{{%auth_item_child}}');
        $this->dropTable('{{%auth_item}}');
        $this->dropTable('{{%auth_rule}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%company}}');
    }

    protected function buildFkClause($delete = '', $update = '')
    {
        return implode(' ', ['', $delete, $update]);
    }
}

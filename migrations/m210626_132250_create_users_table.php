<?php

declare(strict_types=1);

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m210626_132250_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'first_name' => $this->string()->null(),
            'last_name' => $this->string()->null(),
            'phone' => $this->string(20)->null(),
            'created_at' => $this->timestamp(),
            'updated_at'=> $this->timestamp(),
            'token' => $this->string(),
            'expires_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}

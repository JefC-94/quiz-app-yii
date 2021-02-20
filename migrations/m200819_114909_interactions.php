<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m200819_114909_interactions
 */
class m200819_114909_interactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('popup', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'confirm' => $this->string(),
            'cancel' => $this->string(),
            'text1' => Schema::TYPE_TEXT,
            'text2' => Schema::TYPE_TEXT,
            'text3' => Schema::TYPE_TEXT
        ]);

        $this->createTable('keyword', [
            'id' => $this->primaryKey(),
            'keyword' => $this->string(55),
            'slug' => $this->string(55),
            'definition' => $this->string()
        ]);

        $this->createTable('mail_contact', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string(55),
            'lastname' => $this->string(55),
            'slug' => $this->string(55),
            'email' => $this->string(55),
            'status' => $this->string(55)
        ]);

        $this->createTable('testimonial', [
            'id' => $this->primaryKey(),
            'name' => $this->string(55),
            'jobtitle' => $this->string(55),
            'company' => $this->string(55),
            'text' => Schema::TYPE_TEXT
        ]);



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
        $this->dropTable('popup');
        $this->dropTable('keyword');
        $this->dropTable('mail_contact');
        $this->dropTable('testimonial');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200819_114909_interactions cannot be reverted.\n";

        return false;
    }
    */
}

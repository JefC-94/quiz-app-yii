<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m200819_095059_basic_setup_1
 */
class m200819_095059_basic_setup_1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        //CREATE USER TABLES

        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255),
            'email' => $this->string(55),
            'password' => $this->string(255),
            'auth_key' => $this->string(255),
            'access_token' => $this->string(255)
        ]);

        $this->createTable('role', [
            'id' => $this->primaryKey(),
            'rolename' => $this->string(55)
        ]);

        $this->createTable('user_role', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(),

        ]);

        $this->addForeignKey(
            'user_fk',
            'user_role',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'role_fk',
            'user_role',
            'role_id',
            'role',
            'id',
            'CASCADE'
        );

        $this->insert('role', [
            'rolename' => 'admin',
        ]);

        $this->insert('role', [
            'rolename' => 'author',
        ]);

        $this->insert('role', [
            'rolename' => 'editor',
        ]);

        $this->insert('role', [
            'rolename' => 'member',
        ]);

        /* //CREATE PAGES TABLES

        $this->createTable('page', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'seo_title' => $this->string(),
            'seo_keywords' => $this->string(),
            'seo_description' => $this->string(),
            'seo_canonical' => $this->string(),
            'draft' => $this->integer(1),
            'text1' => Schema::TYPE_TEXT,
            'text2' => Schema::TYPE_TEXT,
            'text3' => Schema::TYPE_TEXT,
            'text4' => Schema::TYPE_TEXT,
            'text5' => Schema::TYPE_TEXT,
            'text6' => Schema::TYPE_TEXT,
            'text7' => Schema::TYPE_TEXT,
            'text8' => Schema::TYPE_TEXT,
            'text9' => Schema::TYPE_TEXT,
            'text10' => Schema::TYPE_TEXT,
            'text11' => Schema::TYPE_TEXT,
            'text12' => Schema::TYPE_TEXT,
            'text13' => Schema::TYPE_TEXT,
            'text14' => Schema::TYPE_TEXT,
            'text15' => Schema::TYPE_TEXT,
            'text16' => Schema::TYPE_TEXT,
            'text17' => Schema::TYPE_TEXT,
            'text18' => Schema::TYPE_TEXT,
            'text19' => Schema::TYPE_TEXT,
            'text20' => Schema::TYPE_TEXT,
            'text21' => Schema::TYPE_TEXT,
            'text22' => Schema::TYPE_TEXT,
            'text23' => Schema::TYPE_TEXT,
            'text24' => Schema::TYPE_TEXT,
            'text25' => Schema::TYPE_TEXT,
            'text26' => Schema::TYPE_TEXT,
            'text27' => Schema::TYPE_TEXT,
            'text28' => Schema::TYPE_TEXT,
            'text29' => Schema::TYPE_TEXT,
            'text30' => Schema::TYPE_TEXT,
            'text31' => Schema::TYPE_TEXT,
            'text32' => Schema::TYPE_TEXT,
            'text33' => Schema::TYPE_TEXT,
            'text34' => Schema::TYPE_TEXT,
            'text35' => Schema::TYPE_TEXT,
            'text36' => Schema::TYPE_TEXT,
            'text37' => Schema::TYPE_TEXT,
            'text38' => Schema::TYPE_TEXT,
            'text39' => Schema::TYPE_TEXT,
            'text40' => Schema::TYPE_TEXT
        ]);

        $this->insert('page', [
            'title' => 'Home',
            'slug' => 'home',
            'text1' => 'This is the homepage',
        ]);

        $this->createTable('page_schedule', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer(),
            'based_on' => $this->integer(),
            'schedule_date' => $this->date(),
            'schedule_hour' => $this->integer(2),
            'schedule_min' => $this->integer(2),
            'schedule_index' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk_page_id',
            'page_schedule',
            'page_id',
            'page',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_basedon_id',
            'page_schedule',
            'based_on',
            'page',
            'id',
            'CASCADE',
            'CASCADE'
        ); */

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropTable('page_schedule');
        $this->dropTable('page');

        $this->dropTable('user_role');
        $this->dropTable('users');
        $this->dropTable('role');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200819_095059_basic_setup_1 cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m200819_110500_postmodule
 */
class m200819_110500_postmodule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        //PROFILE TABLE
        $this->createTable('profile', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'firstname' => $this->string(50),
            'lastname' => $this->string(50),
            'name' => $this->string(255),
            'slug' => $this->string(255),
            'image' => $this->string(255),
            'bio' => Schema::TYPE_TEXT,
            'company' => $this->string(50)
        ]);

        $this->addForeignKey(
            'fk_user',
            'profile',
            'user_id',
            'users',
            'id'
        );

        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'body' => Schema::TYPE_TEXT,
            'featured_image' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'draft' => $this->integer(1)->notNull(),
            'seo_title' => $this->string(),
            'seo_keywords' => $this->string(),
            'seo_description' => $this->string(),
        ]);

        $this->addForeignKey(
            'prof_post',
            'post',
            'created_by',
            'profile',
            'id',
            'CASCADE',
            'CASCADE'  
        );

        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'catnaam' => $this->string(),
            'slug' => $this->string()
        ]);

        $this->insert('category', [
            'catnaam' => 'uncategorized',
        ]);

        $this->createTable('post_cat', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'cat_id' => $this->integer()
        ]);

        $this->addForeignKey(
            'postcatpost_fk',
            'post_cat',
            'post_id',
            'post',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'postcatcat_fk',
            'post_cat',
            'cat_id',
            'category',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable('tag', [
            'id' => $this->primaryKey(),
            'tagnaam' => $this->string(),
            'slug' => $this->string()
        ]);

        $this->createTable('post_tag', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'tag_id' => $this->integer()
        ]);

        $this->addForeignKey(
            'posttagpost_fk',
            'post_tag',
            'post_id',
            'post',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'posttagtag_fk',
            'post_tag',
            'tag_id',
            'tag',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable('post_schedule', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'based_on' => $this->integer(),
            'schedule_date' => $this->date(),
            'schedule_hour' => $this->integer(2),
            'schedule_min' => $this->integer(2),
            'schedule_index' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk_postsch_id',
            'post_schedule',
            'post_id',
            'post',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_postbased_id',
            'post_schedule',
            'based_on',
            'post',
            'id',
            'CASCADE',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
        $this->dropTable('post_tag');
        $this->dropTable('post_cat');
        $this->dropTable('post_schedule');

        $this->dropTable('post');
        $this->dropTable('profile');
        $this->dropTable('category');
        $this->dropTable('tag');
    
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200819_110500_postmodule cannot be reverted.\n";

        return false;
    }
    */
}

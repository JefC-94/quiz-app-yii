<?php

use yii\db\Migration;

/**
 * Class m200819_120102_slidermodule
 */
class m200819_120102_slidermodule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('sl_slider', [
            'id' => $this->primaryKey(),
            'name' => $this->string(55),
            'slug' => $this->string(55),
            'width' => $this->integer(),
            'height' => $this->integer(),
            'aspect_ratio' => $this->float(),
            'gap' => $this->integer(),
            'lightbox' => $this->integer(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addForeignKey(
            'fk_slider_created',
            'sl_slider',
            'created_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE'
        );


        $this->createTable('sl_image', [
            'id' => $this->primaryKey(),
            'imagepath' => $this->string()
        ]);

        $this->createTable('sl_slide', [
            'id' => $this->primaryKey(),
            'slider_id' => $this->integer(),
            'image_id' => $this->integer(),
            'slide_index' => $this->integer(2),
            'url' => $this->string(),
            'page' => $this->string(),
            'target' => $this->integer(1)
        ]);

        $this->addForeignKey(
            'slide_slider_fk',
            'sl_slide',
            'slider_id',
            'sl_slider',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'slide_image_fk',
            'sl_slide',
            'image_id',
            'sl_image',
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
        
        $this->dropTable('sl_slide');
        $this->dropTable('sl_slider');
        $this->dropTable('sl_image');
    
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200819_120102_slidermodule cannot be reverted.\n";

        return false;
    }
    */
}

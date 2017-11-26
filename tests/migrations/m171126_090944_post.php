<?php

use yii\db\Migration;

/**
 * Class m171126_090944_post
 */
class m171126_090944_post extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%posts}}',
            [
                'id'             => $this->bigPrimaryKey(),
                'name'           => $this->string(),
                'body'           => $this->text(),
                'cover'           => $this->string(),
                'createdBy'      => $this->integer()->null(),
                'createdAt'      => $this->dateTime(0),
            ]
        );
        $this->addForeignKey('user_rel_fk', '{{%posts}}', 'createdBy', '{{%users}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%posts}}');
    }
 
}

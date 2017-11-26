<?php

use yii\db\Migration;

/**
 * Class m171126_090933_user
 */
class m171126_090933_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%users}}',
            [
                'id'     => $this->primaryKey(),
                'name'  => $this->string()->notNull(),
                'lastName'  => $this->string()->notNull()->defaultValue(''),
                'email'   => $this->string()->notNull(),
                'authKey'   => $this->string(64),
                'passwordHash'   => $this->string(),
                'status'   => $this->string(20)->notNull()->defaultValue('default'),
                'birthday'=>$this->date()->null(),
                'registered'=>$this->dateTime(0),
                'updated'=>$this->dateTime(0)->null(),
                'logged'=>$this->dateTime(0)->null(),
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171126_090933_user cannot be reverted.\n";

        return false;
    }
    */
}

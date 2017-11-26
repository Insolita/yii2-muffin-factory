<?php
/**
 * Created by solly [26.11.17 4:17]
 */

namespace tests\stubs;

use yii\db\ActiveRecord;

/**
 * @property int               $id
 * @property string            $name
 * @property string            $body
 * @property string            $cover
 * @property \tests\stubs\User $creator
 * @property string            $createdAt
 **/
class Post extends ActiveRecord
{
    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'createdBy']);
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }
}

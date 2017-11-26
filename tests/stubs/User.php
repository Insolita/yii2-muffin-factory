<?php
/**
 * Created by solly [26.11.17 4:15]
 */

namespace tests\stubs;

use yii\base\StaticInstanceTrait;
use yii\db\ActiveRecord;

/**
 * @property int                 $id
 * @property string              $name
 * @property string              $lastname
 * @property string              $authKey
 * @property string              $accessToken
 * @property string              $passwordHash
 * @property string              $email
 * @property string             $status
 * @property string              $birthday
 * @property string              $registered
 * @property string              $updated
 * @property string              $logged
 * @property \tests\stubs\Post[] $posts
 */
class User extends ActiveRecord
{
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['createdBy' => 'id']);
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }
}

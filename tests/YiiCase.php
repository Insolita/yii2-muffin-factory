<?php
/**
 * Created by solly [26.11.17 20:08]
 */

namespace tests;

use Yii;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use function expect;

abstract class YiiCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Clean up after test.
     * By default the application created with [[mockApplication]] will be destroyed.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->destroyApplication();
    }
    
    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     *
     * @param array  $config   The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\console\Application')
    {
        $fileConfig = require __DIR__ . '/config/base.php';
        new $appClass(ArrayHelper::merge($fileConfig, $config));
    }
    
    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
        Yii::$container = new Container();
    }
    
    /**
     * @param  \yii\db\ActiveRecord $model
     * @param array                 $attributes
     **/
    protected function canSeeRecord($model, array $attributes = [])
    {
        $result = $model::find()->where($attributes)->limit(1)->one();
        expect($result)->notNull();
    }
    
    /**
     * @param  \yii\db\ActiveRecord $model
     * @param array                 $attributes
     **/
    protected function dontSeeRecord($model, array $attributes = [])
    {
        $result = $model::find()->where($attributes)->limit(1)->one();
        expect($result)->null();
    }
    
    /**
     * @param  \yii\db\ActiveRecord $model
     * @param array                 $attributes
     **/
    protected function canSeeRecords($model, array $attributes = [])
    {
        $result = $model::find()->where($attributes)->all();
        expect($result)->notEmpty();
        expect(count($result))->greaterThan(1);
    }
}

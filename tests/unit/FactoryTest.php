<?php
/**
 * Created by solly [26.11.17 19:08]
 */

namespace tests\unit;

use Codeception\Specify;
use insolita\muffin\Factory;
use tests\stubs\Post;
use tests\stubs\User;
use tests\YiiCase;
use Yii;

class FactoryTest extends YiiCase
{
    use Specify;
    
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }
    
    
    public function testInitialization()
    {
        $factory1 = Yii::createObject(Factory::class);
        $factory2 = Yii::createObject(Factory::class);
        expect($factory1)->same($factory2);
    }
    
    public function testMakeSingle()
    {
        $this->it('can make single model without saving', function () {
            /**@var User $user * */
            $user = factory(User::class)->make();
            expect($user)->isInstanceOf(User::class);
            expect($user->isNewRecord)->true();
            expect($user->status)->equals('default');
            $this->dontSeeRecord(User::class, ['id' => $user->id]);
        });
        $this->it('can make single model with custom state', function () {
            /**@var User $user * */
            $user = factory(User::class)->states('developer')->make();
            expect($user->status)->equals('developer');
            /**@var User $user * */
            $user = factory(User::class)->states('client')->make();
            expect($user->status)->equals('client');
        });
        $this->it('can make single model with custom attributes', function () {
            /**@var User $user * */
            $user = factory(User::class)->make(['email' => 'foo@bar.baz', 'status' => 'special']);
            expect($user->status)->equals('special');
            expect($user->email)->equals('foo@bar.baz');
        });
        $this->it('can make single model with custom attributes using previous generated data', function () {
            /**@var User $user * */
            $user = factory(User::class)->make([
                'name' => function ($data) {
                    return 'special_' . $data['lastName'];
                },
            ]);
            expect($user->name)->startsWith('special');
            expect($user->name)->endsWith($user->lastName);
        });
    }
    
    public function testMakeBulk()
    {
        $this->it('can make bulk models without saving', function () {
            /**@var User[] $users * */
            $users = factory(User::class, 5)->make();
            expect($users)->count(5);
            foreach ($users as $user) {
                expect($user)->isInstanceOf(User::class);
                expect($user->isNewRecord)->true();
                expect($user->status)->equals('default');
            }
        });
        $this->it('can make bulk models with custom state', function () {
            /**@var User[] $users * */
            $users = factory(User::class, 3)->states('developer')->make();
            expect($users)->count(3);
            foreach ($users as $user) {
                expect($user->status)->equals('developer');
            }
            /**@var User[] $users * */
            $users = factory(User::class, 3)->states('client')->make();
            expect($users)->count(3);
            foreach ($users as $user) {
                expect($user->status)->equals('client');
            }
        });
        $this->it('can make bulk models with custom attributes', function () {
            /**@var User[] $users * */
            $users = factory(User::class, 20)->make(['birthday' => '2000-04-01', 'status' => 'special']);
            expect($users)->count(20);
            foreach ($users as $user) {
                expect($user->status)->equals('special');
                expect($user->birthday)->equals('2000-04-01');
            }
        });
        $this->it('can make bulk models with custom attributes using previous generated data', function () {
            /**@var User[] $users * */
            $users = factory(User::class, 5)->make([
                'name' => function ($data) {
                    return 'special_' . $data['lastName'];
                },
            ]);
            foreach ($users as $user) {
                expect($user->name)->startsWith('special');
                expect($user->name)->endsWith($user->lastName);
            }
        });
        $this->it('can make bulk models via special times setter', function () {
            /**@var User[] $users * */
            $users = factory(User::class)->times(4)->make();
            expect($users)->count(4);
        });
    }
    
    public function testRaw()
    {
        $this->it('can return raw generated attributes', function () {
            $data = factory(User::class)->raw();
            expect($data)->internalType('array');
            expect($data)->hasKey('name');
            expect($data)->hasKey('lastName');
            expect($data)->hasKey('birthday');
        });
        $this->it('can return raw generated attributes for bulk models', function () {
            $datas = factory(User::class, 5)->raw();
            expect($datas)->count(5);
            foreach ($datas as $data) {
                expect($data)->internalType('array');
                expect($data)->hasKey('name');
                expect($data)->hasKey('lastName');
                expect($data)->hasKey('birthday');
            }
        });
    
        $this->it('can generate additional attributes with previous generated data', function () {
            $data = factory(User::class)->raw([
                'name' => function ($data) {
                    return 'special_' . $data['lastName'];
                },
            ]);
            expect($data)->hasKey('name');
            expect($data['name'])->startsWith('special');
            expect($data['name'])->endsWith($data['lastName']);
        });
    }
    
    public function testCreating()
    {
        $this->it('can make and persist single model', function () {
            /**@var User $user * */
            $user = factory(User::class)->create();
            expect($user)->isInstanceOf(User::class);
            expect($user->isNewRecord)->false();
            expect($user->status)->equals('default');
            $this->canSeeRecord(User::class, ['id' => $user->id]);
        });
        $this->it('can make and persist single model with state', function () {
            /**@var User $user * */
            $user = factory(User::class)->states('client')->create();
            expect($user)->isInstanceOf(User::class);
            expect($user->isNewRecord)->false();
            expect($user->status)->equals('client');
            $this->canSeeRecord(User::class, ['id' => $user->id]);
        });
        $this->it('can make and persist single model with attribute overriding', function () {
            /**@var User $user * */
            $user = factory(User::class)->states('client')->create(['name'=>'FooBarBaz']);
            expect($user)->isInstanceOf(User::class);
            expect($user->isNewRecord)->false();
            expect($user->name)->equals('FooBarBaz');
            $this->canSeeRecord(User::class, ['id' => $user->id]);
        });
        
        $this->it('can make and persist bulk models', function () {
            /**@var User[] $users * */
            $users = factory(User::class, 15)->create();
            expect($users)->count(15);
            foreach ($users as $user) {
                expect($user)->isInstanceOf(User::class);
                expect($user->isNewRecord)->false();
                expect($user->status)->equals('default');
                $this->canSeeRecord(User::class, ['id' => $user->id]);
            }
        });
    
        $this->it('can create and persist models with dependencies', function () {
            /**@var Post[] $posts * */
            $posts = factory(Post::class, 3)->create();
            expect($posts)->count(3);
            foreach ($posts as $post) {
                expect($post->creator)->isInstanceOf(User::class);
                expect($post->creator->status)->equals('client');
                $this->canSeeRecord(Post::class, ['id' => $post->id]);
                $this->canSeeRecord(User::class, ['id' => $post->creator->id]);
            }
        });
    
        $this->it('can create and persist models with custom dependency', function () {
            /**@var User $user * */
            $user = factory(User::class)->create();
            expect($user->posts)->count(0);
            factory(Post::class, 3)->create(['createdBy'=>$user->id]);
            $user->refresh();
            expect($user->posts)->count(3);
        });
    }
}

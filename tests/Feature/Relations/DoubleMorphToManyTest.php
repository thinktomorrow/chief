<?php

namespace Chief\Tests\Feature\Relations;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\Relation;

class DoubleMorphToManyTest extends TestCase
{
    /**
     * Bootstrap Eloquent.
     *
     * @return void
     */
    public function setUp()
    {
        $db = new DB;

        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $db->bootEloquent();
        $db->setAsGlobal();

        $this->createSchema();
    }

    protected function createSchema()
    {
        $this->schema('default')->create('posts', function ($table) {
            $table->increments('id');
            $table->timestamps();
        });

        $this->schema('default')->create('pages', function ($table) {
            $table->increments('id');
            $table->timestamps();
        });

        $this->schema('default')->create('images', function ($table) {
            $table->increments('id');
            $table->timestamps();
        });

        $this->schema('default')->create('tags', function ($table) {
            $table->increments('id');
            $table->timestamps();
        });

        $this->schema('default')->create('relations', function ($table) {
            $table->string("parent_type");
            $table->unsignedBigInteger("parent_id");
            $table->string("child_type");
            $table->unsignedBigInteger("child_id");
        });
    }

    /**
     * Tear down the database schema.
     *
     * @return void
     */
    public function tearDown()
    {
        foreach (['default'] as $connection) {
            $this->schema($connection)->drop('posts');
            $this->schema($connection)->drop('pages');
            $this->schema($connection)->drop('images');
            $this->schema($connection)->drop('tags');
            $this->schema($connection)->drop('relations');
        }

        Relation::morphMap([], false);
    }

    public function testCreation()
    {
        $post = EloquentManyToManyPolymorphicTestPost::create();
        $image = EloquentManyToManyPolymorphicTestImage::create();
        $tag = EloquentManyToManyPolymorphicTestTag::create();
        $tag2 = EloquentManyToManyPolymorphicTestTag::create();

        $post->tags()->attach($tag->id);
        $post->tags()->attach($tag2->id);
        $image->tags()->attach($tag->id);

        $this->assertEquals(2, $post->tags->count());
        $this->assertEquals(1, $image->tags->count());
        $this->assertEquals(1, $tag->posts->count());
        $this->assertEquals(1, $tag->images->count());
        $this->assertEquals(1, $tag2->posts->count());
        $this->assertEquals(0, $tag2->images->count());
    }

    public function testEagerLoading()
    {
        $post = EloquentManyToManyPolymorphicTestPost::create();
        $tag = EloquentManyToManyPolymorphicTestTag::create();
        $post->tags()->attach($tag->id);

        $post = EloquentManyToManyPolymorphicTestPost::with('tags')->whereId(1)->first();
        $tag = EloquentManyToManyPolymorphicTestTag::with('posts')->whereId(1)->first();

        $this->assertTrue($post->relationLoaded('tags'));
        $this->assertTrue($tag->relationLoaded('posts'));
        $this->assertEquals($tag->id, $post->tags->first()->id);
        $this->assertEquals($post->id, $tag->posts->first()->id);
    }

    /**
     * Helpers...
     */

    /**
     * Get a database connection instance.
     *
     * @return \Illuminate\Database\Connection
     */
    protected function connection($connection = 'default')
    {
        return Eloquent::getConnectionResolver()->connection($connection);
    }

    /**
     * Get a schema builder instance.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected function schema($connection = 'default')
    {
        return $this->connection($connection)->getSchemaBuilder();
    }
}

/**
 * Eloquent Models...
 */
class EloquentManyToManyPolymorphicTestPost extends Eloquent
{
    protected $table = 'posts';
    protected $guarded = [];

    public function tags()
    {
        return $this->morphToMany(EloquentManyToManyPolymorphicTestTag::class, 'taggable');
    }
}

class EloquentManyToManyPolymorphicTestImage extends Eloquent
{
    protected $table = 'images';
    protected $guarded = [];

    public function tags()
    {
        return $this->morphToMany(EloquentManyToManyPolymorphicTestTag::class, 'taggable');
    }
}

class EloquentManyToManyPolymorphicTestTag extends Eloquent
{
    protected $table = 'tags';
    protected $guarded = [];

    public function posts()
    {
        return $this->morphedByMany(EloquentManyToManyPolymorphicTestPost::class, 'taggable');
    }

    public function images()
    {
        return $this->morphedByMany(EloquentManyToManyPolymorphicTestImage::class, 'taggable');
    }
}
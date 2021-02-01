<?php

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Morphable;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\NotFoundMorphKey;

class MorphableTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        MorphableModel::migrateUp();
        Relation::morphMap(['article' => ArticleModel::class]);
    }

    public function tearDown(): void
    {
        // Force empty the morphMap each time because it is kept during the entire testrun.
        Relation::$morphMap = [];

        parent::tearDown();
    }

    /** @test */
    public function it_returns_expected_instance_on_create()
    {
        $model = MorphableModel::create(['morph_key' => ArticleModel::class]);
        $this->assertInstanceOf(ArticleModel::class, $model);

        $instance = MorphableModel::firstOrCreate(['morph_key' => ArticleModel::class]);
        $this->assertInstanceOf(ArticleModel::class, $instance);

        $instance = MorphableModel::updateOrCreate(['morph_key' => ArticleModel::class]);
        $this->assertInstanceOf(ArticleModel::class, $instance);
    }

    /** @test */
    public function it_can_use_key_from_relation_morphmap()
    {
        $instance = MorphableModel::create(['morph_key' => 'article']);
        $this->assertInstanceOf(ArticleModel::class, $instance);

        $instance = MorphableModel::firstOrCreate(['morph_key' => 'article']);
        $this->assertInstanceOf(ArticleModel::class, $instance);

        $instance = MorphableModel::updateOrCreate(['morph_key' => 'article']);
        $this->assertInstanceOf(ArticleModel::class, $instance);
    }

    /** @test */
    public function it_returns_expected_instance_on_find()
    {
        $page = MorphableModel::create(['morph_key' => 'article']);

        $instance = ArticleModel::find($page->id);
        $this->assertInstanceOf(ArticleModel::class, $instance);

        $instance = ArticleModel::findOrFail($page->id);
        $this->assertInstanceOf(ArticleModel::class, $instance);

        $collection = ArticleModel::findMany([$page->id]);
        $this->assertInstanceOf(ArticleModel::class, $collection->first());

        $instance = ArticleModel::where('id', $page->id)->first();
        $this->assertInstanceOf(ArticleModel::class, $instance);
    }

    /** @test */
    public function a_morph_key_is_by_default_based_on_the_morphClass()
    {
        $page = ArticleModel::create();

        $instance = ArticleModel::find($page->id);
        $this->assertInstanceOf(ArticleModel::class, $instance);
        $this->assertEquals($instance->getMorphClass(), $instance->morph_key);
    }

    /** @test */
    public function it_returns_expected_instance_on_relations()
    {
        $this->disableExceptionHandling();
        $parent = ArticleModel::create();

        $child = ArticleModel::create([
            'parent_id' => $parent->id,
        ]);

        $this->assertInstanceOf(ArticleModel::class, $parent->children->first());
    }

    /** @test */
    public function it_throws_exception_when_morph_key_cannot_be_found()
    {
        $this->expectException(NotFoundMorphKey::class);

        ArticleModel::create(['morph_key' => 'xxx']);
    }
}

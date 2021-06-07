<?php

namespace Thinktomorrow\Chief\Tests\Unit\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReferenceCollection;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class ModelReferenceTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function it_can_create_a_collection_from_model_references()
    {
        $first = new ArticlePage(['id' => 1, 'title' => 'first']);
        $second = new ArticlePage(['id' => 2, 'title' => 'second']);

        $modelReferences = ModelReferenceCollection::make([$first, $second])->toModelReferences();

        $this->assertInstanceOf(Collection::class, $modelReferences);
        $this->assertEquals($first->modelReference()->get(), $modelReferences[0]);
        $this->assertEquals($second->modelReference()->get(), $modelReferences[1]);
    }

    /** @test */
    public function it_can_create_collection_id_from_string()
    {
        $model = ArticlePage::create(['title' => 'new title']);

        $this->assertInstanceOf(ModelReference::class, $model->modelReference());

        $modelReference = ModelReference::fromString(ArticlePage::class.'@'.$model->id);
        $this->assertTrue($modelReference->equals($model->modelReference()));
    }

    /** @test */
    public function it_can_create_instance_from_collection_id()
    {
        $first = ArticlePage::create();
        $instance = $first->modelReference()->instance();

        $this->assertInstanceOf(ArticlePage::class, $instance);
        $this->assertEquals($first->id, $instance->id);
    }

    /** @test */
    public function it_can_instantiate_multiple_collection_ids()
    {
        $first = ArticlePage::create();
        $second = ArticlePage::create();

        $instances = ModelReferenceCollection::fromModelReferences(collect([
            $first->modelReference()->get(),
            $second->modelReference()->get(),
        ]));

        $this->assertCount(2, $instances);

        foreach ($instances as $instance) {
            $this->assertInstanceOf(Model::class, $instance);
        }
    }

    /** @test */
    public function it_can_get_morphed_reference()
    {
        Relation::$morphMap = [
            'article' => ArticlePage::class,
        ];

        $article = ArticlePage::create();

        $reference = ModelReference::make(get_class($article), $article->id);

        $this->assertEquals('article@' . $article->id, $reference->getShort());
    }

    /** @test */
    public function it_can_create_from_morphed_reference()
    {
        Relation::$morphMap = [
            'article' => ArticlePage::class,
        ];

        $article = ArticlePage::create();

        $reference = ModelReference::make('article', $article->id);

        $this->assertEquals(get_class($article) .'@'. $article->id, $reference->get());
        $this->assertEquals('article@' . $article->id, $reference->getShort());

        $this->assertInstanceOf(ArticlePage::class, $reference->instance());
    }

    /** @test */
    public function if_morphed_reference_does_not_exist_passed_string_is_used()
    {
        $reference = ModelReference::make('xxx', 1);

        $this->assertEquals('xxx@1', $reference->getShort());
        $this->assertEquals('xxx@1', $reference->get());
    }
}

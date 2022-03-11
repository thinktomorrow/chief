<?php

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReferenceCollection;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class ModelReferenceTest extends TestCase
{
    /** @test */
    public function it_can_make_a_reference()
    {
        $reference = ModelReference::make('classname', 1);

        $this->assertEquals('classname', $reference->className());
        $this->assertEquals(1, $reference->id());
        $this->assertEquals('classname@1', $reference->get());

        $this->assertTrue($reference->is('classname@1'));
        $this->assertTrue($reference->equals(ModelReference::fromString('classname@1')));
    }

    /** @test */
    public function it_cannot_make_a_reference_from_invalid_string()
    {
        $this->expectException(\InvalidArgumentException::class);

        ModelReference::fromString('classname@'); // missing id
    }

    /** @test */
    public function it_can_create_a_collection_from_model_references()
    {
        $first = new ArticlePage(['id' => 1]);
        $second = new ArticlePage(['id' => 2]);

        $modelReferences = ModelReferenceCollection::make([$first, $second])->toModelReferences();

        $this->assertInstanceOf(Collection::class, $modelReferences);
        $this->assertEquals($first->modelReference()->get(), $modelReferences[0]);
        $this->assertEquals($second->modelReference()->get(), $modelReferences[1]);
    }

    /** @test */
    public function a_model_can_have_a_model_reference()
    {
        $article = new ArticlePage(['id' => 1]);

        $this->assertInstanceOf(ModelReference::class, $article->modelReference());
        $this->assertEquals(ArticlePage::class.'@1', $article->modelReference()->get());
    }
}

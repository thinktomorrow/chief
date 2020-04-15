<?php

namespace Thinktomorrow\Chief\Tests\Unit\Templates;

use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Templates\ApplyTemplate;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\TestCase;

class PageTemplateTest extends TestCase
{
    private $source;

    private $target;

    protected function setUp(): void
    {
        parent::setUp();

        $this->source = Single::create();
        $this->source->adoptChild(NewsletterModuleFake::create(['slug' => 'newsletter-one']));
        $this->source->adoptChild(NewsletterModuleFake::create(['slug' => 'newsletter-one', 'owner_id' => $this->source->id, 'owner_type' => $this->source->getMorphClass(), 'dynamic_title' => 'dynamic title']));
        $this->source->adoptChild(ArticlePageFake::create());

        $this->target = Single::create();

        app(ApplyTemplate::class)->handle(get_class($this->source), $this->source->id, get_class($this->target), $this->target->id);
    }

    /** @test */
    public function a_page_template_duplicates_relations()
    {
        // children (pagebuilder relations)
        $sourceChildren = $this->source->children();
        $targetChildren = $this->target->children();

        $this->assertCount(3, $targetChildren);

        // Page specific module - is copied
        $this->assertTrue($targetChildren[1]->isPageSpecific());
        $this->assertNotContains($targetChildren[1]->id, $sourceChildren->pluck('id'));
        $this->assertEquals('dynamic title', $targetChildren[1]->dynamic_title);

        // General modules and page references are not copied
        $this->assertFalse($targetChildren[0]->isPageSpecific());
        $this->assertEquals($sourceChildren[0]->id, $targetChildren[0]->id);
        $this->assertEquals($sourceChildren[2]->id, $targetChildren[2]->id);
    }
}

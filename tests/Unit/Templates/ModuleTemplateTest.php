<?php

namespace Thinktomorrow\Chief\Tests\Unit\Templates;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Templates\ApplyTemplate;
use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;

class ModuleTemplateTest extends TestCase
{
    /** @test */
    public function a_module_can_be_duplicated()
    {
        $this->disableExceptionHandling();
        $source = NewsletterModuleFake::create(['slug' => 'source', 'values' => ['dynamic_title' => 'source title'], 'content:nl' => 'nl content']);
        $source->saveFragment(Fragment::fromNew('fragment-key', ['title' => 'title-one']), 1);
        app(AddAsset::class)->add($source, UploadedFile::fake()->image('image.png'), 'images', 'nl');

        $target = NewsletterModuleFake::create(['slug' => 'target']);

        app(ApplyTemplate::class)->handle(get_class($source), $source->id, get_class($target), $target->id);

        $target->refresh();

        // Dynamic attribute value
        $this->assertEquals('source title', $target->dynamic_title);

        // Translations
        $this->assertCount(1, $target->translations()->get());
        $this->assertEquals('nl content', $target->content);

        // Fragments
        $this->assertCount(1, $target->fragments()->get());
        $this->assertEquals('title-one', $target->getFragments('fragment-key')->first()->title);

        // Assets
        $this->assertCount(1, $target->assets('images'));
        $this->assertEquals($source->asset('images')->id, $target->asset('images')->id); // Asset is the same asset as  the source
    }
}

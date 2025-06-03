<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\ContextDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\EditFragment;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class UploadingFileWhenEditingFragmentTest extends ChiefTestCase
{
    use RefreshDatabase;
    use TestingFileUploads;

    private ContextOwner $model;

    private Fragment $fragment;

    private Testable $component;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();

        [, $this->fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->model, Hero::class, null, 0, ['title' => 'initial value']);

        $this->component = Livewire::test(EditFragment::class, [
            'context' => ContextDto::fromContext(ContextModel::first(), $this->model->modelReference(), 'ownerLabel', 'ownerAdminUrl'),
            'parentComponentId' => 'xxx',
            'model' => $this->model,
        ]);
    }

    public function test_it_can_see_existing_files(): void
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        app(AddAsset::class)->handle($this->fragment->getFragmentModel(), $asset, 'thumb', 'nl', 0, []);

        $this->component
            ->call('open', [
                'fragmentId' => $this->fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])
            ->assertStatus(200)
            ->assertSet('form.files.thumb.nl', [PreviewFile::fromAsset($asset)]);
    }

    public function test_it_uploads_file()
    {
        $filePath = $this->storeFakeImageOnLivewireDisk('image.png');
        $previewFile = PreviewFile::fromTemporaryUploadedFile(TemporaryUploadedFile::createFromLivewire($filePath));

        $this->assertDatabaseCount('assets', 0);
        $this->assertDatabaseCount('assets_pivot', 0);

        $this->component
            ->call('open', [
                'fragmentId' => $this->fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])->set('form.files.thumb.nl', [$previewFile]);

        $this->component->call('save');

        $this->assertDatabaseCount('assets', 1);
        $this->assertDatabaseCount('assets_pivot', 1);
    }

    public function test_it_attaches_file()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);

        $this->assertDatabaseCount('assets', 1);
        $this->assertDatabaseCount('assets_pivot', 0);

        $this->component
            ->call('open', [
                'fragmentId' => $this->fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])->set('form.files.thumb.nl', [$previewFile])
            ->call('save');

        $this->assertDatabaseCount('assets', 1);
        $this->assertDatabaseCount('assets_pivot', 1);
    }
}

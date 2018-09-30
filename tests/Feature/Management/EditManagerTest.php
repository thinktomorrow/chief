<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Models\AssetUploader;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\TestCase;

class EditManagerTest extends TestCase
{
    private $manager;
    private $model;

    protected function setUp()
    {
        parent::setUp();

        ManagedModelFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('fakes', ManagerFake::class);

        $this->model = ManagedModelFake::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->manager = app(ManagerFake::class)->manage($this->model);
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $this->asAdmin()->get($this->manager->route('edit'))
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        $this->get($this->manager->route('edit'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function it_has_the_default_media_values()
    {
        // Add image to model
        $source = UploadedFile::fake()->image('tt-favicon.png');
        $asset = AssetUploader::upload($source);
        $asset->attachToModel($this->model, 'avatar');

        $this->assertEquals('tt-favicon.png', $this->model->getFilename('avatar'));

        $this->assertCount(1, $this->manager->getFieldValue('avatar'));
        $this->assertEquals([
            'avatar' => [
                (object)[
                    'id' => $asset->id,
                    'filename' => $asset->getFilename(),
                    'url' => $asset->getFileUrl(),
                ]
            ]
        ], $this->manager->getFieldValue('avatar'));
    }
}

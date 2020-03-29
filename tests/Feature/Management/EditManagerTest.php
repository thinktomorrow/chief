<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Management\Exceptions\NonExistingRecord;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class EditManagerTest extends TestCase
{
    private $manager;
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);

        $this->model = ManagedModelFakeFirst::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->manager = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()))->manage($this->model);
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $response = $this->asAdmin()->get($this->manager->route('edit'));
        $response->assertViewIs('chief::back.managers.edit');
        $response->assertStatus(200);
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

        $asset = app(AddAsset::class)->add($this->model, $source, 'avatar', 'nl');

        config()->set(['app.fallback_locale' => 'nl']);

        $this->assertEquals('tt-favicon.png', $this->model->asset('avatar')->filename());

        $fieldValue = $this->manager->fields()['avatar']->model($this->model)->getValue();

        $this->assertCount(1, $fieldValue);
        $this->assertEquals([
            (object)[
                'id'        => $asset->id,
                'filename'  => $asset->filename(),
                'url'       => $asset->url(),
            ]
        ], $fieldValue);
    }

    /** @test */
    public function it_cant_edit_a_softdeleted_model()
    {
        $this->disableExceptionHandling();
        $this->expectException(NonExistingRecord::class);

        $this->model->delete();

        //use the static url here otherwise the existingmodel function errors before this triggers.
        $response = $this->asAdmin()->get('admin/manage/managed_model_first/1/edit');
        $response->assertStatus(500);
    }
}

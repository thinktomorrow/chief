<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class UpdateManagerTest extends TestCase
{
    private $fake;
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);

        $this->model = ManagedModelFakeFirst::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->fake = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()))->manage($this->model);
    }

    /** @test */
    public function it_can_update_a_field()
    {
        $this->asAdmin()
            ->put($this->fake->route('update'), [
                'title' => 'foobar-updated',
            ]);

        $this->assertEquals('foobar-updated', $this->model->fresh()->title);
    }

    /** @test */
    public function it_can_update_a_field_with_custom_column_name()
    {
        $this->asAdmin()
            ->put($this->fake->route('update'), [
                'custom' => 'custom-updated',
            ]);

        $this->assertEquals('custom-updated', $this->model->fresh()->custom_column);
    }

    /** @test */
    public function it_can_update_a_translatable_field()
    {
        $this->asAdmin()
            ->put($this->fake->route('update'), [
                'trans' => [
                    'nl' => [
                        'title_trans' => 'title-nl-updated',
                        'content_trans' => 'content-nl-updated',
                    ],
                    'en' => [
                        'title_trans' => 'title-en-updated',
                    ]
                ],
            ]);

        $this->assertEquals('title-nl-updated', $this->model->fresh()->title_trans);
        $this->assertEquals('content-nl-updated', $this->model->fresh()->content_trans);

        $this->assertEquals('title-en-updated', $this->model->fresh()->getTranslationFor('title_trans', 'en'));
        $this->assertNull($this->model->fresh()->getTranslationFor('content_trans', 'en'));
    }

    /** @test */
    public function it_can_update_a_media_field()
    {
        $this->asAdmin()
            ->put($this->fake->route('update'), [
                'files' => [
                    'hero' => [
                        'new' => [
                            $this->dummySlimImagePayload('tt-favicon.png'),
                        ]
                    ]
                ],
            ]);

        $this->assertEquals('tt-favicon.png', $this->model->asset('hero')->filename());
    }

    /** @test */
    public function it_can_upload_a_document()
    {
        $this->asAdmin()
            ->put($this->fake->route('update'), [
                'files' => [
                    'doc' => [
                        'new' => [
                            $this->dummyDocument('tt-document.pdf'),
                        ]
                    ]
                ],
            ]);

        $this->assertEquals('tt-document.pdf', $this->model->asset('doc')->filename());
    }

    /** @test */
    public function it_can_upload_both_images_and_documents()
    {
        $this->asAdmin()
            ->put($this->fake->route('update'), [
                'files' => [
                    'hero' => [
                        'new' => [
                            $this->dummySlimImagePayload('tt-favicon.png'),
                        ]
                    ],
                    'doc' => [
                        'new' => [
                            $this->dummyDocument('tt-document.pdf'),
                        ]
                    ]
                ],
            ]);

        $this->assertEquals('tt-favicon.png', $this->model->asset('hero')->filename());
        $this->assertEquals('tt-document.pdf', $this->model->asset('doc')->filename());
    }
}

<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class StoreManagerTest extends TestCase
{
    private $fake;

    protected function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);

        $this->fake = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()));
    }

    /** @test */
    public function it_can_create_a_field()
    {
        $this->asAdmin()
            ->post($this->fake->route('store'), [
                'title' => 'foobar-created',
            ]);

        $this->assertEquals('foobar-created', ManagedModelFakeFirst::first()->title);
    }

    /** @test */
    public function it_can_create_a_field_with_custom_column_name()
    {
        $this->asAdmin()
            ->post($this->fake->route('store'), [
                'custom' => 'custom-created',
            ]);

        $this->assertEquals('custom-created', ManagedModelFakeFirst::first()->custom_column);
    }

    /** @test */
    public function it_can_create_a_translatable_field()
    {
        $this->asAdmin()
            ->post($this->fake->route('store'), [
                'trans' => [
                    'nl' => [
                        'title_trans' => 'title-nl-created',
                        'content_trans' => 'content-nl-created',
                    ],
                    'en' => [
                        'title_trans' => 'title-en-created',
                    ]
                ],
            ]);

        $first = ManagedModelFakeFirst::first();

        $this->assertEquals('title-nl-created', $first->title_trans);
        $this->assertEquals('content-nl-created', $first->content_trans);

        $this->assertEquals('title-en-created', $first->getTranslationFor('title_trans', 'en'));
        $this->assertNull($first->getTranslationFor('content_trans', 'en'));
    }

    /** @test */
    public function it_can_create_a_media_field()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()
            ->post($this->fake->route('store'), [
                'files' => [
                    'hero' => [
                        'new' => [
                            $this->dummySlimImagePayload('tt-favicon.png'),
                        ]
                    ]
                ],
            ]);

        $this->assertEquals('tt-favicon.png', ManagedModelFakeFirst::first()->asset('hero')->filename());
    }
}

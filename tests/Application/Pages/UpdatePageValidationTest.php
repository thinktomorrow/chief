<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

final class UpdatePageValidationTest extends ChiefTestCase
{
    private $model;
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();

        app(Register::class)->model(ArticlePage::class, PageManager::class);

        $this->model = ArticlePage::create(['title' => 'Foobar']);
        $this->manager = app(Registry::class)->manager(ArticlePage::managedModelKey());

        config()->set('app.fallback_locale', 'nl');
    }

    /** @test */
    public function a_required_field_can_be_validated()
    {
        $this->assertValidation(
            new ArticlePage(),
            'title',
            $this->payload(['title' => '']),
            $this->manager->route('edit', $this->model),
            $this->manager->route('update', $this->model),
            1,
            'put'
        );
    }

    /** @test */
    public function a_required_translatable_field_can_be_validated()
    {
        $this->assertValidation(
            new ArticlePage(),
            'trans.nl.content_trans',
            $this->payload(['trans.nl.content_trans' => '']),
            $this->manager->route('edit', $this->model),
            $this->manager->route('update', $this->model),
            1,
            'put'
        );
    }

    /** @test */
    public function a_non_default_translatable_field_is_not_validated_if_entire_translation_is_empty()
    {
        $response = $this->actingAs($this->developer(), 'chief')
            ->put($this->manager->route('update', $this->model), $this->payload(['trans.en.content_trans' => '']));

        $this->assertNull(session('errors'));
    }

    protected function payload($overrides = [])
    {
        $params = [
            'title' => 'title updated',
            'custom' => 'custom updated',
            'trans' => [
                'nl' => [
                    'content_trans' => 'content_trans nl updated',
                ],
                'en' => [
                    'content_trans' => 'content_trans en updated',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }
}

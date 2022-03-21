<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Forms;

use function config;
use Illuminate\Support\Arr;
use function session;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

final class ValidateFormTest extends ChiefTestCase
{
    private ArticlePage $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setupAndCreateArticle(['title' => 'Foobar']);

        config()->set('app.fallback_locale', 'nl');
    }

    /** @test */
    public function a_required_field_can_be_validated()
    {
        $this->assertValidation(
            new ArticlePage(),
            ['title' => 'The title field is required.'],
            $this->payload(['title' => '']),
            $this->manager($this->model)->route('edit', $this->model),
            $this->manager($this->model)->route('update', $this->model),
            1,
            'put'
        );
    }

    /** @test */
    public function a_field_can_be_validated()
    {
        $this->assertValidation(
            new ArticlePage(),
            ['title' => 'The title must be at least 4 characters.'],
            $this->payload(['title' => 'xx']),
            $this->manager($this->model)->route('edit', $this->model),
            $this->manager($this->model)->route('update', $this->model),
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
            $this->payload(['trans.nl.content_trans' => '', 'trans.en.content_trans' => '']),
            $this->manager($this->model)->route('edit', $this->model),
            $this->manager($this->model)->route('update', $this->model),
            1,
            'put'
        );
    }

    /** @test */
    public function a_required_translatable_field_can_be_validated_when_null_is_passed()
    {
        $this->assertValidation(
            new ArticlePage(),
            'trans.nl.content_trans',
            $this->payload(['trans.nl.content_trans' => null]),
            $this->manager($this->model)->route('edit', $this->model),
            $this->manager($this->model)->route('update', $this->model),
            1,
            'put'
        );
    }

    /** @test */
    public function a_non_default_translatable_field_is_not_validated_if_entire_translation_is_empty()
    {
        $response = $this->actingAs($this->developer(), 'chief')
            ->put($this->manager($this->model)->route('update', $this->model), $this->payload(['trans.en.content_trans' => '']));

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

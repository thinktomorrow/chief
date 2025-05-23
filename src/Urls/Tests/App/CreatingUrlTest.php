<?php

namespace App;

use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Exceptions\UrlAlreadyExists;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class CreatingUrlTest extends ChiefTestCase
{
    private UrlRepository $repository;

    private UrlApplication $application;

    private Visitable $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(UrlRepository::class);
        $this->application = app(UrlApplication::class);

        $this->model = $this->setUpAndCreateArticle();
    }

    public function test_it_creates_new_url_with_base_url_segment()
    {
        $this->application->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'my-slug', 'online'
        ));

        $url = UrlRecord::first();

        $this->assertNotNull($url);
        $this->assertEquals('nl-base/my-slug', $url->slug);
        $this->assertEquals('nl', $url->site);
        $this->assertEquals(LinkStatus::online, LinkStatus::from($url->status));
    }

    public function test_it_creates_new_url_without_prepending_base_url_segment()
    {
        $this->application->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'my-slug', 'online', false
        ));

        $url = UrlRecord::first();

        $this->assertNotNull($url);
        $this->assertEquals('my-slug', $url->slug);
        $this->assertEquals('nl', $url->site);
        $this->assertEquals(LinkStatus::online, LinkStatus::from($url->status));
    }

    public function test_it_cannot_create_url_that_already_exists()
    {
        $this->application->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'my-slug', 'online'
        ));

        $this->expectException(UrlAlreadyExists::class);

        $model2 = $this->setUpAndCreateArticle([], false);

        $this->application->create(new CreateUrl(
            $model2->modelReference(), 'nl', 'my-slug', 'online'
        ));
    }

    public function test_it_redirects_existing_url_when_creating_new_slug()
    {
        $existingId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'old-slug',
            'status' => LinkStatus::online->value,
        ]);

        $recordId = $this->application->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'new-slug', 'online'
        ));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $existingId,
            'slug' => 'old-slug',
            'redirect_id' => $recordId,
        ]);
    }

    public function test_it_converts_diacritics_to_ascii(): void
    {
        $recordId = $this->application->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'foobér', 'online'
        ));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $recordId,
            'slug' => 'nl-base/foober',
        ]);

        $this->assertDatabaseMissing('chief_urls', [
            'id' => $recordId,
            'slug' => 'nl-base/foobér',
        ]);
    }

    public function test_it_converts_spaces_to_hypen(): void
    {
        $recordId = $this->application->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'foo bar', 'online'
        ));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $recordId,
            'slug' => 'nl-base/foo-bar',
        ]);

        $this->assertDatabaseMissing('chief_urls', [
            'id' => $recordId,
            'slug' => 'nl-base/foo bar',
        ]);
    }
}

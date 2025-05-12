<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\ReactivateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UpdateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Exceptions\UrlAlreadyExists;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class UrlApplicationTest extends ChiefTestCase
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

    public function test_it_updates_url_and_creates_redirect_if_slug_changed()
    {
        $existingId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'original-slug',
            'status' => LinkStatus::online->value,
        ]);

        $this->application->update(new UpdateUrl(
            $existingId,
            'updated-slug',
            'online',
        ));

        $updated = $this->repository->find($existingId);
        $this->assertEquals('nl-base/updated-slug', $updated->slug);

        $this->assertDatabaseHas('chief_urls', [
            'slug' => 'original-slug',
            'redirect_id' => $updated->id,
        ]);
    }

    public function test_it_updates_url_without_prepending_base_url_segment()
    {
        $existingId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'original-slug',
            'status' => LinkStatus::online->value,
        ]);

        $this->application->update(new UpdateUrl(
            $existingId,
            'updated-slug',
            'online',
            false,
        ));

        $updated = $this->repository->find($existingId);
        $this->assertEquals('updated-slug', $updated->slug);
    }

    public function test_update_fails_if_slug_is_already_used_by_active_url()
    {
        $this->expectException(\InvalidArgumentException::class);

        $model1 = $this->model;
        $model2 = $this->setUpAndCreateArticle([], false);

        $existingId = $this->application->create(new CreateUrl($model1->modelReference(), 'nl', 'duplicate-slug', 'online'));
        $otherRecordId = $this->application->create(new CreateUrl($model2->modelReference(), 'nl', 'another-slug', 'online'));

        $this->expectException(UrlAlreadyExists::class);
        $this->application->update(new UpdateUrl($otherRecordId, 'duplicate-slug', 'online'));

        $this->assertDatabaseMissing('chief_urls', [
            'id' => $otherRecordId,
            'slug' => 'duplicate-slug',
        ]);
    }

    public function test_it_can_reactivate_redirect_url(): void
    {
        $activeId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'active-slug',
            'status' => LinkStatus::online->value,
        ]);

        $redirectId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'redirect-slug',
            'redirect_id' => $activeId,
            'status' => LinkStatus::online->value,
        ]);

        $this->application->reactivateUrl(new ReactivateUrl($redirectId));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $redirectId,
            'slug' => 'redirect-slug',
            'redirect_id' => null,
        ]);

        $this->assertDatabaseHas('chief_urls', [
            'id' => $activeId,
            'slug' => 'active-slug',
            'redirect_id' => $redirectId,
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
}

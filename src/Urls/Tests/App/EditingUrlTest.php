<?php

namespace App;

use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UpdateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Exceptions\UrlAlreadyExists;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class EditingUrlTest extends ChiefTestCase
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

    public function test_it_converts_diacritics_to_ascii(): void
    {
        $recordId = $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foobar', 'online'));

        $this->application->update(new UpdateUrl(
            $recordId, 'foobér', 'online'
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
        $recordId = $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'original-slug', 'online'));

        $this->application->update(new UpdateUrl(
            $recordId, 'foo bar', 'online'
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

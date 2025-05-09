<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\ChangeHomepageUrl;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UpdateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Exceptions\HomepageSlugNotAllowed;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class ProtectsAgainstHomepageSlugTest extends ChiefTestCase
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

    public function test_it_cannot_create_homepage_slug_as_root_slash(): void
    {
        $this->expectException(HomepageSlugNotAllowed::class);

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', '/', 'online'));

        $this->assertCount(0, UrlRecord::all());
    }

    public function test_it_cannot_create_homepage_slug_as_empty_slug(): void
    {
        $this->expectException(HomepageSlugNotAllowed::class);

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', '', 'online'));

        $this->assertCount(0, UrlRecord::all());
    }

    public function test_it_cannot_update_homepage_slug_as_root_slash(): void
    {
        $this->expectException(HomepageSlugNotAllowed::class);

        $recordId = $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foobar', 'online'));

        $this->application->update(new UpdateUrl($recordId, '/', 'online'));

        $this->assertCount(1, UrlRecord::all());
        $this->assertEquals('nl-base/foobar', UrlRecord::first()->slug);
    }

    public function test_it_cannot_update_homepage_slug_as_empty_slug(): void
    {
        $this->expectException(HomepageSlugNotAllowed::class);

        $recordId = $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foobar', 'online'));

        $this->application->update(new UpdateUrl($recordId, '', 'online'));

        $this->assertCount(1, UrlRecord::all());
        $this->assertEquals('nl-base/foobar', UrlRecord::first()->slug);
    }

    public function test_it_can_change_to_homepage_url(): void
    {
        $recordId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'active-slug',
            'status' => LinkStatus::online->value,
        ]);

        $this->application->changeHomepageUrl(new ChangeHomepageUrl($this->model->modelReference(), 'nl'));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $recordId,
            'slug' => '/',
            'redirect_id' => null,
        ]);

        $this->assertDatabaseHas('chief_urls', [
            'slug' => 'active-slug',
            'redirect_id' => $recordId,
        ]);
    }

    public function test_it_can_force_change_homepage_url(): void
    {
        $recordId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'active-slug',
            'status' => LinkStatus::online->value,
        ]);

        $model2 = $this->setUpAndCreateArticle([], false);

        $existingHomepageId = $this->repository->create($model2->modelReference(), [
            'site' => 'nl',
            'slug' => '/',
            'status' => LinkStatus::online->value,
        ]);

        $this->application->changeHomepageUrl(new ChangeHomepageUrl($this->model->modelReference(), 'nl'));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $recordId,
            'slug' => '/',
            'redirect_id' => null,
        ]);

        $this->assertDatabaseMissing('chief_urls', [
            'id' => $existingHomepageId,
            'slug' => '/',
        ]);
    }
}

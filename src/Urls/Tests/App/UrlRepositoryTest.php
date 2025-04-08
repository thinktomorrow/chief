<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Exceptions\UrlAlreadyExists;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class UrlRepositoryTest extends ChiefTestCase
{
    private UrlRepository $repository;

    private Visitable $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new UrlRepository;
        $this->model = $this->setUpAndCreateArticle();
    }

    public function test_it_creates_a_url_record()
    {
        $id = $this->repository->create($this->model->modelReference(), [
            'slug' => 'test-slug',
            'site' => 'nl',
            'status' => 'online',
        ]);

        $this->assertDatabaseHas('chief_urls', [
            'id' => $id,
            'slug' => 'test-slug',
            'site' => 'nl',
            'model_type' => 'article_page',
            'model_id' => '1',
        ]);
    }

    public function test_it_updates_a_url_record()
    {
        $id = $this->repository->create($this->model->modelReference(), [
            'slug' => 'initial-slug',
            'site' => 'nl',
            'status' => 'online',
        ]);

        $this->repository->update($id, ['slug' => 'updated-slug', 'status' => 'offline']);

        $this->assertDatabaseHas('chief_urls', [
            'id' => $id,
            'slug' => 'updated-slug',
            'status' => 'offline',
        ]);
    }

    public function test_it_finds_a_url_record_by_id()
    {
        $id = $this->repository->create($this->model->modelReference(), [
            'slug' => 'sluggy',
            'site' => 'nl',
            'status' => 'online',
        ]);

        $record = $this->repository->find($id);

        $this->assertEquals('sluggy', $record->slug);
    }

    public function test_it_finds_url_by_model_and_site()
    {
        $this->repository->create($this->model->modelReference(), [
            'slug' => 'my-slug',
            'site' => 'nl',
            'status' => 'online',
        ]);

        $record = $this->repository->findActiveByModel($this->model->modelReference(), 'nl');

        $this->assertNotNull($record);
        $this->assertEquals('my-slug', $record->slug);
    }

    public function test_it_cannot_create_same_slug_site_twice(): void
    {
        $this->expectException(UrlAlreadyExists::class);

        $this->repository->create($this->model->modelReference(), [
            'slug' => 'duplicate',
            'site' => 'nl',
            'status' => 'online',
        ]);

        // 2nd record
        $this->repository->create(ModelReference::fromString('other@456'), [
            'slug' => 'duplicate',
            'site' => 'nl',
            'status' => 'online',
        ]);
    }

    public function test_it_returns_multiple_urls_for_given_slug_and_site()
    {
        $id = $this->repository->create($this->model->modelReference(), [
            'slug' => 'duplicate',
            'site' => 'nl',
            'status' => 'online',
        ]);

        $result = $this->repository->findBySlug('duplicate', 'nl');

        $this->assertEquals($id, $result->id);
    }

    public function test_it_finds_only_active_slug()
    {
        // One active, one redirect
        $this->repository->create($this->model->modelReference(), [
            'slug' => 'sluggy',
            'site' => 'nl',
            'status' => 'online',
        ]);

        $this->repository->create($this->model->modelReference(), [
            'slug' => 'sluggy-non-active',
            'site' => 'nl',
            'status' => 'online',
            'redirect_id' => 999,
        ]);

        $active = $this->repository->findActiveUrlBySlug('sluggy', 'nl');

        $this->assertNotNull($active);
        $this->assertNull($active->redirect_id);
    }

    public function test_it_finds_identical_urls_of_model_excluding_id()
    {
        $originalId = $this->repository->create($this->model->modelReference(), [
            'slug' => 'original',
            'site' => 'nl',
            'status' => 'online',
        ]);

        UrlRecord::create([
            'slug' => 'same',
            'redirect_id' => $originalId,
            'site' => 'nl',
            'model_type' => $this->model->modelReference()->shortClassName(),
            'model_id' => $this->model->modelReference()->id(),
        ]);

        $results = $this->repository->getIdenticalUrlsOfModel($this->model->modelReference(), 'same', 'nl', $originalId);

        $this->assertCount(1, $results);
        $this->assertNotEquals($originalId, $results->first()->id);
    }

    public function test_it_finds_identical_urls_of_other_models()
    {
        UrlRecord::create([
            'slug' => 'shared',
            'site' => 'nl',
            'model_type' => 'other',
            'model_id' => '999',
        ]);

        $results = $this->repository->getIdenticalUrlsOfOtherModels($this->model->modelReference(), 'shared', 'nl');

        $this->assertCount(1, $results);
    }
}

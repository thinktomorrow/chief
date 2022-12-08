<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\IndexRepository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Thinktomorrow\Chief\ManagedModels\Filters\FilterPresets;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\Managers\Repositories\DefaultIndexRepository;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class DefaultIndexRepositoryTest extends TestCase
{
    private DefaultIndexRepository $repository;
    private $article;
    private $article2;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $this->article = ArticlePage::create(['title' => 'foobar']);
        $this->article2 = ArticlePage::create(['title' => 'stoner']);

        $this->repository = new DefaultIndexRepository(ArticlePage::query());
    }

    public function testItCanRetrieveIndexRows()
    {
        $rows = $this->repository->getRows();

        $this->assertInstanceOf(LengthAwarePaginator::class, $rows);
        $this->assertCount(2, $rows);
    }

    public function testItCanAdjustWithCustomQueries()
    {
        $rows = $this->repository->adjustQuery([
            function ($builder) {
                $builder->where('title', 'stoner');
            },
        ], [])->getRows();

        $this->assertCount(1, $rows);
        $this->assertEquals($this->article2->id, $rows->first()->id);
    }

    public function testItCanAdjustWithFilters()
    {
        $parameterBag = ['title' => 'foobar'];

        $filters = new Filters([
            FilterPresets::column('title', ['title']),
        ]);

        $rows = $this->repository->adjustQuery($filters->allApplicable($parameterBag), $parameterBag)->getRows();

        $this->assertCount(1, $rows);
        $this->assertEquals($this->article->id, $rows->first()->id);
    }

    public function testItCanPaginateIndexRows()
    {
        $rows = $this->repository->getRows(1);

        $this->assertInstanceOf(LengthAwarePaginator::class, $rows);
        $this->assertEquals(2, $rows->total());
        $this->assertEquals(1, $rows->count());
        $this->assertCount(1, $rows);
    }

    public function testItCanSortIndexRows()
    {
        $this->repository->adjustQuery([
            function ($builder) {
                $builder->orderBy('title', 'DESC');
            },
        ], []);

        $rows = $this->repository->getRows();

        $this->assertInstanceOf(LengthAwarePaginator::class, $rows);
        $this->assertEquals(2, $rows->count());

        $this->assertEquals($this->article2->id, $rows->first()->id);
        $this->assertEquals($this->article->id, $rows[1]->id);
    }
}

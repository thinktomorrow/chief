<?php

namespace Thinktomorrow\Chief\Tests\Unit\Repository;

use Thinktomorrow\Chief\ManagedModels\Repository\EloquentIndexRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\Fakes\NestableArticlePage;

class IndexRepositoryTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->resource(NestableArticlePage::class);
        NestableArticlePage::migrateUp();
    }

    public function test_it_can_get_empty_results()
    {
        foreach($this->getRepositories() as $repository) {
            $this->assertIsIterable($repository->getResults());
            $this->assertEmpty($repository->getResults());
            $this->assertIsIterable($repository->getNestableResults());
            $this->assertEmpty($repository->getNestableResults());
            $this->assertIsIterable($repository->getPaginatedResults());
            $this->assertEmpty($repository->getPaginatedResults());
        }
    }

    public function test_it_can_get_results()
    {
        $this->markTestSkipped();

        foreach($this->getRepositories() as $repository) {
            $results = $repository->getResults();
        }
    }

    public function test_it_can_get_paginated_results()
    {
        $this->markTestSkipped();

        foreach($this->getRepositories() as $repository) {
            $results = $repository->getPaginatedResults();
        }
    }

    public function test_it_can_get_nestable_results()
    {
        NestableArticlePage::create(['id' => '1', 'parent_id' => null]);
        NestableArticlePage::create(['id' => '2', 'parent_id' => '1']);
        NestableArticlePage::create(['id' => '3', 'parent_id' => '1']);
        NestableArticlePage::create(['id' => '4', 'parent_id' => '3']);

        foreach($this->getRepositories() as $repository) {
            $results = $repository->getNestableResults();

            $this->assertCount(4, $results);
        }
    }

    private function getRepositories(): iterable
    {
        yield app(EloquentIndexRepository::class, ['resourceKey' => NestableArticlePage::resourceKey()]);
    }
}

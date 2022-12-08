<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels\Filters;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\InputFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\SelectFilter;

class FiltersTest extends TestCase
{
    /** @test */
    public function it_can_collect_filters()
    {
        $filters = Filters::make([
            InputFilter::make('title', function(){}),
            SelectFilter::make('status', function(){}),
        ]);

        $this->assertInstanceOf(Filters::class, $filters);
    }

    /** @test */
    public function it_can_only_collect_valid_filters()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Filters([
            InputFilter::make('title', function(){}),
            SelectFilter::make('status', function(){}),
            new class(){}
        ]);
    }

    /** @test */
    public function it_can_add_filter()
    {
        $filters = Filters::make([
            InputFilter::make('title', function(){}),
            SelectFilter::make('status', function(){}),
        ]);

        $this->assertCount(2, $filters->all());

        $addedFilters = $filters->add(InputFilter::make('name', function(){}),);

        $this->assertCount(3, $addedFilters->all());
        $this->assertCount(2, $filters->all()); // immutable
    }

    /** @test */
    public function it_can_merge_filters()
    {
        $filters = Filters::make([
            InputFilter::make('title', function(){}),
            SelectFilter::make('status', function(){}),
        ]);

        $filterForMerge = Filters::make([
            InputFilter::make('name', function(){}),
        ]);

        $mergedFilters = $filters->merge($filterForMerge);

        $this->assertCount(3, $mergedFilters->all());
        $this->assertCount(2, $filters->all()); // immutable
    }

    /** @test */
    public function it_overwrites_filter_with_same_name()
    {
        $filters = Filters::make([
            InputFilter::make('title', function(){}),
            SelectFilter::make('status', function(){}),
        ]);

        $this->assertCount(2, $filters->all());

        $addedFilters = $filters->add($usedFilter = InputFilter::make('title', function(){}),);

        $this->assertCount(2, $addedFilters->all());

        $this->assertSame($usedFilter, $addedFilters[1]);
    }


    /** @test */
    public function it_can_check_if_there_are_any_filters()
    {
        $emptyFilters = Filters::make([]);
        $this->assertTrue($emptyFilters->isEmpty());
        $this->assertFalse($emptyFilters->any());
        $this->assertFalse($emptyFilters->anyRenderable());

        $filters = Filters::make([
            InputFilter::make('title', function(){}),
            SelectFilter::make('status', function(){}),
        ]);

        $this->assertFalse($filters->isEmpty());
        $this->assertTrue($filters->any());
        $this->assertTrue($filters->anyRenderable());
    }

    /** @test */
    public function it_can_get_all_filters()
    {
        $filters = Filters::make([
            $firstFilter = InputFilter::make('title', function(){}),
            $secondFilter = SelectFilter::make('status', function(){}),
        ]);

        $this->assertCount(2, $filters->all());
        $this->assertSame($firstFilter, $filters[0]);
        $this->assertSame($secondFilter, $filters[1]);
    }

    /** @test */
    public function it_can_get_all_applicable_filters()
    {
        $filters = Filters::make([
            $firstFilter = InputFilter::make('title', function(){}),
            SelectFilter::make('status', function(){}),
        ]);

        $this->assertCount(1, $filters->allApplicable(['title' => 'foobar']));
        $this->assertSame($firstFilter, $filters[0]);
    }

    /** @test */
    public function it_can_render_all_filters()
    {
        $filters = Filters::make([
            $firstFilter = InputFilter::make('title', function(){}),
            $secondFilter = SelectFilter::make('status', function(){}),
        ]);

        $this->assertEquals($firstFilter->render([]) . $secondFilter->render([]), $filters->render([]));
    }

    /** @test */
    public function it_can_query_all_applicable_filters()
    {
        ArticlePage::migrateUp();
        $article = ArticlePage::create(['title' => 'foobar']);

        $filters = Filters::make([
            InputFilter::make('title', function ($builder, $value, $parameterBag) {
                $builder->where('title', $value);
            })
        ]);

        // Query with results
        $builder = ArticlePage::query();
        $filters->apply($builder, ['title' => 'foobar']);
        $this->assertCount(1, $builder->get());

        // Query without results
        $builder = ArticlePage::query();
        $filters->apply($builder, ['title' => 'xxx']);
        $this->assertCount(0, $builder->get());
    }


}

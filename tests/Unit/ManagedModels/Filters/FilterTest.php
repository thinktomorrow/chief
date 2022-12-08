<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels\Filters;

use Thinktomorrow\Chief\ManagedModels\Filters\Filter;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\CheckboxFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\InputFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\RadioFilter;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\SelectFilter;
use Thinktomorrow\Chief\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class FilterTest extends TestCase
{
    /** @test */
    public function itCanCreateAFilter()
    {
        $filter = CheckboxFilter::make('status', function () {
        });

        $this->assertInstanceOf(Filter::class, $filter);
        $this->assertEquals('status', $filter->queryKey());
    }

    /** @test */
    public function itCanCreateAllPresetFilters()
    {
        foreach($this->allPresetFilters() as $presetFilter) {
            $this->assertInstanceOf(Filter::class, $presetFilter);
        }
    }

    /** @test */
    public function itCanProvideFieldInformation()
    {
        $filter = CheckboxFilter::make('status', function () {
        });

        $filter->options([
            'one',
            'two',
        ]);
        $filter->label('label filter');
        $filter->description('description filter');
        $filter->placeholder('placeholder filter');
        $filter->default('default filter');
        $filter->value('value filter');

        $this->assertEquals([
            'id'          => 'status',
            'name'        => 'status',
            'label'       => 'label filter',
            'description' => 'description filter',
            'value'       => 'value filter',
            'placeholder' => 'placeholder filter',
            'default'     => 'default filter',
            'options'     => [
                'one',
                'two',
            ],
        ], $this->getViewData($filter));
    }

    /** @test */
    public function itCanHaveValueFromParameterBag()
    {
        $filter = InputFilter::make('title', function () {
        });

        $this->assertEquals('foobar', $this->getViewData($filter, ['title' => 'foobar'])['value']);
    }

    /** @test */
    public function itIsApplicableWhenParameterIsPresentAndNotNull()
    {
        $filter = CheckboxFilter::make('status', function () {
        });

        // Filter with given parameter values are applicable
        $this->assertTrue($filter->applicable(['status' => 'xxx']));
        $this->assertTrue($filter->applicable(['status' => true]));
        $this->assertTrue($filter->applicable(['status' => false]));

        // Null values are considered not applicable
        $this->assertFalse($filter->applicable(['status' => null]));

        // With existing value the filter is applicable.
        $this->assertTrue($filter->value('xxx')->applicable(['status' => null]));
    }

    /** @test */
    public function itCanCallQuery()
    {
        ArticlePage::migrateUp();
        $article = ArticlePage::create(['title' => 'foobar']);

        $filter = InputFilter::make('title', function ($builder, $value, $parameterBag) {
            $builder->where('title', $value);
        });

        // Query with results
        $builder = ArticlePage::query();
        $filter->query($builder, ['title' => 'foobar']);
        $this->assertCount(1, $builder->get());

        // Query without results
        $builder = ArticlePage::query();
        $filter->query($builder, ['title' => 'xxx']);
        $this->assertCount(0, $builder->get());
    }

    /** @test */
    public function itCanBeRendered()
    {
        foreach($this->allPresetFilters() as $presetFilter) {
            $this->assertNotNull($presetFilter->render([]));
        }
    }

    /**
     * Filter viewData is protected, so we provide
     * a way to retrieve viewData via this method
     *
     */
    private function getViewData(Filter $filter, array $parameterBag = []): array
    {
        $reflection = new \ReflectionClass($filter);
        $method = $reflection->getMethod('viewData');

        $method->setAccessible(true);

        return $method->invokeArgs($filter, [$parameterBag]);
    }

    private function allPresetFilters(): array
    {
        return [
            CheckboxFilter::make('xxx', function () {
            }),
            RadioFilter::make('xxx', function () {
            }),
            InputFilter::make('xxx', function () {
            }),
            SelectFilter::make('xxx', function () {
            }),
            HiddenFilter::make('xxx', function () {
            }),
        ];
    }
}

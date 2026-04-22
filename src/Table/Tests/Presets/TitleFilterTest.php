<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Presets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Table\Filters\Presets\TitleFilter;
use Thinktomorrow\Chief\Table\Tests\Fixtures\ModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TreeModelFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class TitleFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ModelFixture::migrateUp();
        TreeModelFixture::migrateUp();
    }

    public function test_title_filter_treats_dot_notation_as_relation_when_relation_exists(): void
    {
        $parent = ModelFixture::create(['title' => 'Parent bromic']);
        ModelFixture::create(['title' => 'Child result', 'parent_id' => $parent->id]);
        ModelFixture::create(['title' => 'Other child']);

        $query = ModelFixture::query();
        $filter = TitleFilter::makeDefault(['parent.title'], []);

        $filter->getQuery()($query, 'bromic');

        $this->assertSame(
            ['Child result'],
            $query->orderBy('title')->pluck('title')->all()
        );
    }

    public function test_title_filter_treats_dot_notation_as_qualified_column_when_relation_does_not_exist(): void
    {
        ModelFixture::create(['title' => 'Bromic heater']);
        ModelFixture::create(['title' => 'Remote control']);

        $query = ModelFixture::query();
        $filter = TitleFilter::makeDefault(['chief_table_model_fixtures.title'], []);

        $filter->getQuery()($query, 'bromic');

        $this->assertSame(
            ['Bromic heater'],
            $query->orderBy('title')->pluck('title')->all()
        );
    }

    public function test_default_title_filter_splits_search_terms_with_and_logic(): void
    {
        TreeModelFixture::create(['title' => 'Bromic terrasverwarmer']);
        TreeModelFixture::create(['title' => 'Afstandsbediening los verkrijgbaar']);
        TreeModelFixture::create(['title' => 'Bromic met afstandsbediening']);

        $query = TreeModelFixture::query();
        $filter = TitleFilter::makeDefault(['title'], []);

        $filter->getQuery()($query, 'Bromic afstands');

        $this->assertSame(
            ['Bromic met afstandsbediening'],
            $query->orderBy('title')->pluck('title')->all()
        );
    }

    public function test_title_filter_can_search_on_full_term_when_strict_is_enabled(): void
    {
        TreeModelFixture::create(['title' => 'Bromic terrasverwarmer']);
        TreeModelFixture::create(['title' => 'Bromic met afstandsbediening']);
        TreeModelFixture::create(['title' => 'Bromic afstands module']);

        $query = TreeModelFixture::query();
        $filter = TitleFilter::makeDefault(['title'], [], 'values', true);

        $filter->getQuery()($query, 'Bromic afstands');

        $this->assertSame(
            ['Bromic afstands module'],
            $query->orderBy('title')->pluck('title')->all()
        );
    }
}

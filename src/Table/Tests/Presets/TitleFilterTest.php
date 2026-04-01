<?php

namespace Thinktomorrow\Chief\Table\Tests\Presets;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinktomorrow\Chief\Table\Filters\Presets\TitleFilter;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TreeModelFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class TitleFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        TreeModelFixture::migrateUp();
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
